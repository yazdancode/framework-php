console.clear();

setTimeout(function (qualifiedName, value){
    document.querySelector('input[type="checkbox"]').setAttribute('checked', value);
},100);