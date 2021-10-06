function countChars(obj,targetId,max){

    var maxLength = max;
    var strLength = obj.value.length;
    var target = document.getElementById(targetId);
    var span = document.querySelector("#" + targetId + " .dynamic");

    span.innerText = strLength + "/" + maxLength;

    if (strLength > maxLength) {
        obj.classList.add("is-invalid");
        target.classList.add("invalid-feedback");
    } else if (target.classList.contains("invalid-feedback")) {
        obj.classList.remove("is-invalid");
        target.classList.remove("invalid-feedback");
    }
}