function setDropdown(inputId, buttonId, displayText, value) {
    document.getElementById(inputId).value = value;
    document.getElementById(buttonId).innerText = displayText;
}