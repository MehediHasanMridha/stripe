function tri_tbody(id,td,type){
    let list = [].slice.call(document.getElementById(id).children);
    console.log(list);
    list.sort((a,b)=>{
        if(type==="int"){
            let av=parseFloat(a.children[td].innerText);
            let bv=parseFloat(b.children[td].innerText);
            return bv-av;
        }
        if(type==="int-reverse"){
            let av=parseFloat(a.children[td].innerText);
            let bv=parseFloat(b.children[td].innerText);
            return av-bv;
        }
        if(type==="ponderation"){
            let av=a.children[td].dataset.id;
            let bv=b.children[td].dataset.id;
            return av-bv;
        }
        else{
            let av=a.children[td].innerText;
            let bv=b.children[td].innerText;
            return av.localeCompare(bv);
        }
    });
    console.log(list);
    document.getElementById(id).innerHTML = '';
    list.forEach((child)=>document.getElementById(id).appendChild(child))
}
