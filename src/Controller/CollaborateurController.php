<?php

namespace App\Controller;
use App\Entity\Evaluation;
use App\Entity\Collaborateur;
use App\Form\CollaborateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CollaborateurController extends AbstractController
{
    /**
     * @Route("/collaborateur/nouveau", name="collaborateur_nouveau", methods={"GET", "POST"})
     */
    #[Route('/user', name: 'userform', methods: ['GET', 'POST'])]
    public function nouveau(Request $request, EntityManagerInterface $entityManager,): Response
    {// Création ou récupération de l'évaluation pour ce collaborateur
       

   
            
        
        $collaborateur = new Collaborateur();
        $form = $this->createForm(CollaborateurType::class, $collaborateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Vérifier si l'email est déjà utilisé
            $existingCollaborateur = $entityManager->getRepository(Collaborateur::class)->findOneBy(['email' => $collaborateur->getEmail()]);
            if ($existingCollaborateur) {
                $this->addFlash('error', 'Cette adresse email est déjà associée à un collaborateur.');
                return $this->redirectToRoute('collaborateur_nouveau');
            }

            try {
                // Création ou récupération de l'évaluation pour ce collaborateur
                $evaluation = new Evaluation();
                $evaluation->setMoyenne(0); // Initialisation à 0
                $evaluation->setInterpretation("Interprétation indéfinie");
                
                // Associer l'évaluation au collaborateur
                $collaborateur->setEvaluation($evaluation);
                $entityManager->persist($evaluation);

                $entityManager->persist($collaborateur);
                $entityManager->flush();

                // Extrait prénom et nom de l'email
                [$localPart] = explode('@', $collaborateur->getEmail());
                [$prenom, $nom] = explode('.', $localPart);

                 // Capitaliser la première lettre du prénom et du nom
$prenom = ucfirst(strtolower($prenom));
$nom = ucfirst(strtolower($nom));

// Afficher un message de bienvenue
$this->addFlash('success', "Salut <strong>$prenom $nom !!!</strong><br><br>Prépare-toi à un voyage vers la découverte de toi-même ! Réponds aux questions et laisse la magie opérer. C'est parti pour une exploration de bien-être comme jamais !");
return $this->redirectToRoute('home');

return $this->redirectToRoute('home');
 // Redirige vers la page d'accueil après ajout
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'ajout du collaborateur : ' . $e->getMessage());
            }
        }

        return $this->render('home/user.html.twig', [
            'form' => $form->createView(),
            'collaborateur' => $collaborateur,
         
        ]);
    }
    
    
    
}
