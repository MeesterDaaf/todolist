require('./bootstrap');

document.addEventListener('change', (e) => {
    if (!e.target?.form?.matches('.todo-item__form')) {
        return
    }

    const action = e.target.form.action;
    const data = new FormData(e.target.form);

    axios.post(action, Object.fromEntries(data.entries()))
        .catch(err => {
            console.error(err)
            e.target.checked = !e.target.checked
        }).then(function (response) {
            
            //there is a response.. now what shall we do with it....
            if(e.target.checked && response.data.setup != undefined){
                let child = document.querySelector('.joke');
                
                let setup = response.data.setup;
                let punch  = response.data.punchline;

                child.innerHTML = setup;
                child.append(punch);
            }
            
          
          });
})
