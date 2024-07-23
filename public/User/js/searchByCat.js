window.onload=() => {
  console.log('louay');
  const searchInput = document.querySelector("#search-input");
  searchInput.addEventListener('input',() =>{
    searchVal=searchInput.value ;
    const Params = new URLSearchParams();
    Params.append('key',searchVal);

    const Url = new URL(window.location.href);
    fetch(Url.pathname  + "?" + Params.toString(),{
    headers: {
      "X-Requested-With": "XMLHttpRequest"
    }
  }).then(response =>response.json()
  ).then(data =>{
    const content=document.querySelector("#content");
    content.innerHTML=data.content ;
  }).catch( e=> alert(e));
});
}


