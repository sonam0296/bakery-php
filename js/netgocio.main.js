var $array_langs = ["blog", "be", "sv", "eg", "ec", "do", "dm", "dj", "af", "al", "dz", "as", "ad", "ao", "ai", "aq", "ag", "ar", "am", "aw", "au", "at", "az", "bs", "bh", "bd", "bb", "by", "bo", "bz", "bj", "bm", "bt", "ba", "bw", "bv", "br", "io", "bn", "bg", "bf", "bi", "kh", "cm", "ca", "cv", "ky", "cf", "td", "cl", "cn", "cx", "cc", "co", "km", "cd", "cg", "ck", "cr", "ci", "hr", "cu", "cy", "cz", "dk", "gq", "er", "ee", "et", "fo", "fj", "fi", "fr", "gf", "pf", "tf", "ga", "gm", "ge", "de", "gh", "gi", "gr", "gd", "gp", "gu", "gt", "gn", "gw", "gy", "ht", "hm", "va", "hn", "hk", "hu", "is", "in", "id", "ir", "iq", "ie", "il", "it", "jm", "jp", "jo", "kz", "ke", "ki", "kp", "kr", "kw", "kg", "la", "lv", "lb", "ls", "lr", "ly", "li", "lt", "lu", "mo", "mk", "mg", "mw", "my", "mv", "ml", "mt", "mh", "mq", "mr", "mu", "yt", "mx", "fm", "md", "mc", "mn", "ms", "ma", "mz", "na", "nr", "np", "nl", "an", "nc", "nz", "ni", "ne", "ng", "nu", "nf", "mp", "no", "om", "pk", "pw", "pa", "pg", "py", "pe", "ph", "pn", "pl", "pr", "qa", "re", "ro", "ru", "rw", "sh", "kn", "lc", "pm", "vc", "ws", "sm", "st", "sa", "sn", "rs", "sc", "sl", "sg", "sk", "si", "sb", "so", "za", "gs", "es", "lk", "sd", "sr", "sz", "se", "ch", "sy", "tw", "tj", "tz", "th", "tl", "tg", "tk", "to", "tt", "tn", "tr", "tm", "tc", "tv", "ug", "ua", "ae", "gb", "us", "uy", "uz", "vu", "ve", "vn", "vg", "wf", "eh", "ye", "zm", "zw", "pt", "es", "en"],
	_server_hostname = document.location.href.substring(0,document.location.href.lastIndexOf("/"))+"/",
	_includes_path = "",
    _assets_path = "",
	_images_path = "";

$array_langs.forEach(function(element) {
    if(_server_hostname.indexOf("/"+element+"/")>0){
        _server_hostname = _server_hostname.replace("/"+element, "");  
    }
});


_includes_path = _server_hostname+"includes/";
_images_path = _server_hostname+"imgs/";
_assets_path = _server_hostname+"js/";
