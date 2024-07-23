console.log('louay');
window.onload=() => {
    console.log('louay');
    const searchInput = document.querySelector("#search-course");
    searchInput.addEventListener('input',() =>{
      searchVal=searchInput.value ;
      const Params = new URLSearchParams();
      Params.append('key',searchVal);
      console.log(Params.toString())
  
      const Url = new URL(window.location.href);
      fetch(Url.pathname  + "?" + Params.toString(),{
      headers: {
        "X-Requested-With": "XMLHttpRequest"
      }
    }).then(response =>response.json()
    ).then(data =>{
      const content=document.querySelector("#coursecontent");
      content.innerHTML=data.content ;
    }).catch( e=> alert(e));
  });
  }
  