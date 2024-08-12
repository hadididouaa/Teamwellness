<?php
namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Entity\Collaborateur;
use App\Entity\Evaluation;
use App\Entity\Reponses;
use App\Repository\QuestionRepository;
use App\Repository\EvaluationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class QuestionnaireController extends AbstractController
{
    #[Route('/questions/{id}', name: 'questionnair')]
    public function questionnair(
        $id,
        Request $request,
        PaginatorInterface $paginator,
        EntityManagerInterface $entityManager,
        QuestionRepository $questionRepository,
        EvaluationRepository $evaluationRepository,
        SessionInterface $session,
        LoggerInterface $logger
    ): Response {
        $collaborateur = $entityManager->getRepository(Collaborateur::class)->find($id);
        if (!$collaborateur) {
            throw $this->createNotFoundException('Collaborateur non trouvé pour cet ID.');
        }

        $currentPage = $request->query->getInt('page', 1);
        $questions = $paginator->paginate(
            $questionRepository->findAllQuestions(),
            $currentPage,
            1
        );

        if ($request->isMethod('POST')) {
            $questionId = $request->request->get('question_id');
            $responseValue = (int) $request->request->get('response');

    
             // Store the response in the session, scoped by collaborateur ID
             $responses = $session->get('responses_' . $collaborateur->getId(), []);
             $responses[$questionId] = $responseValue;
             $session->set('responses_' . $collaborateur->getId(), $responses);
 
             // Handle saving to the database
             $question = $questionRepository->find($questionId);
             $evaluation = $collaborateur->getEvaluation();
             $existingReponse = $entityManager->getRepository(Reponses::class)->findOneBy([
                 'question' => $question,
                 'collaborateur' => $collaborateur,
                 'evaluation' => $evaluation
             ]);


            if ($existingReponse) {
                $existingReponse->setScorereponse($responseValue);
            } else {
                $reponse = new Reponses();
                $reponse->setScorereponse($responseValue);
                $reponse->setCollaborateur($collaborateur);
                $reponse->setQuestion($question);
                $reponse->setEvaluation($evaluation);
                $entityManager->persist($reponse);
            }

            $entityManager->flush();

            $nextQuestion = $questionRepository->findNextQuestion($questionId);
            if ($nextQuestion) {
                return $this->redirectToRoute('questionnair', ['id' => $collaborateur->getId(), 'page' => $currentPage + 1]);
            } else {
                // Retrieve all responses for evaluation
                $responses = $entityManager->getRepository(Reponses::class)->findBy(['evaluation' => $evaluation]);
                $responseValues = array_map(fn($response) => $response->getScorereponse(), $responses);

                // Create an array of question numbers with score 0
                $zeroScoreQuestions = [];
                foreach ($responses as $response) {
                    if ($response->getScorereponse() === 0) {
                        $zeroScoreQuestions[] = $response->getQuestion()->getNum();
                    }
                }

                // Define critical combinations
                $criticalCombinations = [
                    [1, 5], [1, 8], [1, 11], [3, 9], [3, 12], [4, 7],
                    [4, 13], [5, 8], [5, 11], [7, 13], [8, 11], [9, 12]
                ];

                // Check if any critical combination has questions with a score of 0
                $criticalConditionMet = false;
                foreach ($criticalCombinations as $combo) {
                    if (in_array($combo[0], $zeroScoreQuestions) && in_array($combo[1], $zeroScoreQuestions)) {
                        $criticalConditionMet = true;
                        break;
                    }
                }

                if ($criticalConditionMet) {
                    $interpretation = "Pour jouir du bien-être dans la vie, un appui psychologique est comme un baume pour le corps et l'âme";

                    $evaluation->setInterpretation($interpretation);
                    $entityManager->persist($evaluation);
                    $entityManager->flush();
                } else {
                    $averageScore = array_sum($responseValues);
                    $interpretation = ($averageScore < 13) ?
                        "Pour jouir du bien-être dans la vie, un appui psychologique est comme un baume pour le corps et l'âme" :
                        ($averageScore >= 13 && $averageScore <= 17 ?
                            "Un petit soin psychologique est comme une saveur de chocolat lors d’une journée remplie" :
                            ($averageScore >= 18 && $averageScore <= 26 ?
                                "Bien dans son corps et son âme, prêt à danser la salsa avec les défis de la vie" :
                                "Interprétation non déterminée."));
                    $evaluation->setMoyenne($averageScore);
                    $evaluation->setInterpretation($interpretation);
                    $entityManager->persist($evaluation);
                    $entityManager->flush();
                }

                // Redirect to the evaluation page with the results
                return $this->redirectToRoute('ev', ['id' => $collaborateur->getId()]);
            }
        }

        // Load responses from session, scoped by collaborateur ID
        $responses = $session->get('responses_' . $collaborateur->getId(), []);


        return $this->render('home/list.html.twig', [
            'questions' => $questions,
            'collaborateur' => $collaborateur,
            'currentPage' => $currentPage,
            'responses' => $responses
        ]);
    }

    #[Route('/evaluation/{id}', name: 'ev')]
    public function evaluation($id, EntityManagerInterface $entityManager): Response {
        $collaborateur = $entityManager->getRepository(Collaborateur::class)->find($id);
        if (!$collaborateur) {
            throw $this->createNotFoundException('Collaborateur non trouvé pour cet ID.');
        }

        $evaluation = $collaborateur->getEvaluation();

        return $this->render('home/evaluation.html.twig', [
            'interpretation' => $evaluation->getInterpretation(),
            'averageScore' => $evaluation->getMoyenne(),
            'collaborateur' => $collaborateur,
        ]);
    }

    #[Route('/statistiques/{id}', name: 'statistiques')]
    public function statistiques(
        $id,
        EvaluationRepository $evaluationRepository,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): Response {
        $collaborateur = $entityManager->getRepository(Collaborateur::class)->find($id);
        if (!$collaborateur) {
            throw $this->createNotFoundException('Collaborateur non trouvé pour cet ID.');
        }

        $evaluations = $evaluationRepository->findAll();
        $totalEvaluations = 0;
        $besoinAssistanceCount = 0;
        $recommandationAssistanceCount = 0;
        $bonneEtatCount = 0;

        foreach ($evaluations as $evaluation) {
            $interpretation = $evaluation->getInterpretation();
            if ($interpretation) {
                if (str_contains($interpretation, 'un appui psychologique est comme un baume pour le corps et l\'âme')) {
                    $besoinAssistanceCount++;
                    $totalEvaluations++;
                } elseif (str_contains($interpretation, 'Un petit soin psychologique est comme une saveur de chocolat lors d’une journée remplie')) {
                    $recommandationAssistanceCount++;
                    $totalEvaluations++;
                } elseif (str_contains($interpretation, 'Bien dans son corps et son âme, prêt à danser la salsa avec les défis de la vie')) {
                    $bonneEtatCount++;
                    $totalEvaluations++;
                }
            }
        }

        if ($totalEvaluations === 0) {
            $percentages = [
                'besoin_assistance' => 0,
                'recommandation_assistance' => 0,
                'bonne_etat' => 0,
            ];
        } else {
            $percentages = [
                'besoin_assistance' => round(($besoinAssistanceCount / $totalEvaluations) * 100),
                'recommandation_assistance' => round(($recommandationAssistanceCount / $totalEvaluations) * 100),
                'bonne_etat' => round(($bonneEtatCount / $totalEvaluations) * 100),
            ];
        }

        return $this->render('home/statistiques.html.twig', [
            'collaborateur' => $collaborateur,
            'percentages' => $percentages,
            'totalEvaluations' => $totalEvaluations
        ]);
    }

}
