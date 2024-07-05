
const select = document.createElement('select');
const script = document.scripts[document.scripts.length - 1];

const langs_req = fetch('/api/i11n/languages')
    .then(async data => {
        return await data.json();
    })
    .then(async json => {
        console.log(json)
        for (const lang of json.body.languages) {
            console.log(lang)
            let new_opt = document.createElement('option');
            new_opt.value = lang.code;
            new_opt.innerText = lang.name;
            select.appendChild(new_opt);
        }

        select.value = json.body.current;

        script.parentElement.insertBefore(select, script);
})

let separator = (window.location.href.indexOf("?")===-1)?"?":"&";

select.onchange = function () {
    window.location.href = window.location.href + separator + `lang=${select.value}`;
}