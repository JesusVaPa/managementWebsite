function clearForm() {
    $('#group-form').get(0).reset();
    $("#create-button").prop("disabled", false);
    $("#clear-button").prop("disabled", true);
    $("#update-button").prop("disabled", true);
    $("#delete-button").prop("disabled", true);
    document.getElementById("group-selector").value = "";
}

function updateClearButtonState() {
    let dirtyElements = $("#group-form")
        .find('*')
        .filter(":input")
        .filter((index, element) => {
            return $(element).val();
        });
    if (dirtyElements.length > 0) {
        $("#clear-button").prop("disabled", false);
    } else {
        $("#clear-button").prop("disabled", true);
    }
}


function getFormDataAsUrlEncoded() {
    let formData = new FormData();
    formData.set("id", $("#group-id").val());
    formData.set("name", $("#group-name").val());
    formData.set("description", $("#group-description").val());
    formData.set("dateCreated", $("#user-date-created").val());
    formData.set("dateLastModified", $("#user-date-last-modified").val());
    $(".group-users").each((index, inputElem) => {
        console.log(inputElem);
        formData.set(inputElem.name, $(inputElem).prop("checked"));
    });
    console.log(Object.fromEntries(formData));
    return (new URLSearchParams(formData)).toString();
}

function fillFormFromResponseObject(entityObject) {
    if ('id' in entityObject) {
        $("#group-id").val(entityObject.id);
    }
    if ('name' in entityObject) {
        $("#group-name").val(entityObject.name);
    }
    if ('description' in entityObject) {
        $("#group-description").val(entityObject.description);
    }
    if ('dateCreated' in entityObject) {
        $("#user-date-created").val(entityObject.dateCreated);
    }
    if ('dateLastModified' in entityObject) {
        $("#user-date-last-modified").val(entityObject.dateLastModified);
    }

    // uncheck all users
    $(".group-users").each((index, inputElem) => {
        $(inputElem).prop("checked", false)
    });

    if ('users' in entityObject) {
        if (typeof entityObject.users === "object") {
            console.log(Object.keys(entityObject.users));
            Object.keys(entityObject.users).forEach((value) => {
                $(`#group-user-${value}`).prop("checked", true);
            });
        }
    }

    $("#create-button").prop("disabled", true);
    $("#clear-button").prop("disabled", false);
    $("#update-button").prop("disabled", false);
    $("#delete-button").prop("disabled", false);
}

function displayResponseError(responseErrorObject) {
    let errorContainer = $(".error-display");
    let classnameContainer = $("#error-class");
    let messageContainer = $("#error-message");
    let previousContainer = $("#error-previous");
    let stacktraceContainer = $("#error-stacktrace");
    if ('exception' in responseErrorObject && typeof responseErrorObject.exception === "object") {
        let exception = responseErrorObject.exception;
        classnameContainer.empty();
        messageContainer.empty();
        previousContainer.empty();
        if ('exceptionClass' in exception) {
            classnameContainer.html(exception.exceptionClass);
        }
        if ('message' in exception) {
            messageContainer.html(exception.message);
        }
        while ('previous' in exception && typeof exception.previous === "object") {
            exception = exception.previous;
            if ('exceptionClass' in exception && 'message' in exception) {
                previousContainer.append(`Caused by: ${exception.exceptionClass}: ${exception.message}<br/>`);
            }
        }
    }
    stacktraceContainer.empty();
    if ('stacktrace' in responseErrorObject) {
        stacktraceContainer.html(responseErrorObject.stacktrace.replace(/\r\n/g, '\n'));
    }
    errorContainer.slideToggle().delay(5000).slideToggle();

}

function loadGroup() {
    let selectedRecordId = document.getElementById("group-selector").value;

    const options = {
        "url": `${API_GROUP_URL}?groupId=${selectedRecordId}`,
        "method": "get",
        "dataType": "json"
    };

    $.ajax(options)
        .done((data, status, jqXHR) => {
            console.log("Received data: ", data);
            fillFormFromResponseObject(data);
        })
        .fail((jqXHR, textstatus, error) => {
            if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
                displayResponseError(jqXHR.responseJSON);
            }
        });
}

function createGroup() {
    const options = {
        "url": `${API_GROUP_URL}`,
        "method": "post",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };

    $.ajax(options)
        .done((data, status, jqXHR) => {
            console.log("Received data: ", data);

            if ('name' in data) {
                let selector = document.getElementById("group-selector");
                let newOptionElement = document.createElement("option");
                newOptionElement.value = data.id;
                newOptionElement.innerHTML = `${data.name}`;
                selector.appendChild(newOptionElement);
                selector.value = data.id;
            }
            fillFormFromResponseObject(data);
        })
        .fail((jqXHR, textstatus, error) => {
            if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
                displayResponseError(jqXHR.responseJSON);
            }
        });
}

function updateGroup() {
    const options = {
        "url": `${API_GROUP_URL}`,
        "method": "put",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };

    $.ajax(options)
        .done((data, status, jqXHR) => {
            console.log("Received data: ", data);

            // Replace the text in the selector with the updated values
            let formIdValue = document.getElementById("group-id").value;
            if ('name' in data) {
                let selector = /** @type {HTMLSelectElement} */ document.getElementById("group-selector");
                // Note: voluntary non-identity equality check ( == instead of === ): disable warning
                // noinspection EqualityComparisonWithCoercionJS
                [...selector.options].filter(elem => elem.value == formIdValue).forEach(elem => {
                    elem.innerHTML = `${data.name}`;
                });
            }
            fillFormFromResponseObject(data);
        })
        .fail((jqXHR, textstatus, error) => {
            if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
                displayResponseError(jqXHR.responseJSON);
            }
        });
}

function deleteGroup() {
    const options = {
        "url": `${API_GROUP_URL}`,
        "method": "delete",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };

    $.ajax(options)
        .done((data, status, jqXHR) => {
            console.log("Received data: ", data);
            let formIdValue = document.getElementById("group-id").value;
            if (formIdValue) {
                let selector = /** @type {HTMLSelectElement} */ document.getElementById("group-selector");
                // Note: voluntary non-identity equality check ( == instead of === ): disable warning
                // noinspection EqualityComparisonWithCoercionJS
                [...selector.options].filter(elem => elem.value == formIdValue).forEach(elem => elem.remove());
                selector.value = "";
            }
            clearForm();
        })
        .fail((jqXHR, textstatus, error) => {
            if ('responseJSON' in jqXHR && typeof jqXHR.responseJSON === "object") {
                displayResponseError(jqXHR.responseJSON);
            }
        });
}

document.getElementById("view-instance-button").onclick = loadGroup;
document.getElementById("clear-button").onclick = clearForm;
document.getElementById("create-button").onclick = createGroup;
document.getElementById("update-button").onclick = updateGroup;
document.getElementById("delete-button").onclick = deleteGroup;
$("#group-form").on("change", ":input", updateClearButtonState);