export const disableScroll = () => document.body.classList.add('lock');

export const enableScroll = () => document.body.classList.remove('lock');

export const preloader = (active = false) => {
    if (active) document.querySelector('.preloader').classList.add('active');
    if (!active) document.querySelector('.preloader').classList.remove('active');
}

export const parseString = (str) => {
    const symbols = {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        "\"": "&quot;",
        "'": "&apos;",
        "'": "&#8217;",
        '"': ["&#8221;", "&#8220;"],
        "-": "&#8211;"
    }
    let newStr = str;

    for (const [key, symbol] of Object.entries(symbols)) {
        if (symbol instanceof Array) {
            symbol.forEach(s => newStr = newStr.replaceAll(s, key))
        } else {
            newStr = newStr.replaceAll(symbol, key);
        }   
    }
    return newStr;
}