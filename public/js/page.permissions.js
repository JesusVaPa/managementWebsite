function clearForm() {
    $('#permission-form').get(0).reset();
    $("#create-button").prop("disabled", false);
    $("#clear-button").prop("disabled", true);
    $("#update-button").prop("disabled", true);
    $("#delete-button").prop("disabled", true);
    document.getElementById("permission-selector").value = "";
}

function updateClearButtonState() {
    let dirtyElements = $("#permission-form")
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
    formData.set("permissionId", $("#permission-id").val());
    formData.set("permission_name", $("#permission-name").val());
    formData.set("permission_description", $("#permission-description").val());
    formData.set("permission_identifier", $("#permission-identifier").val());
    formData.set("dateCreated", $("#permission-date-created").val());
    formData.set("dateLastModified", $("#permission-date-last-modified").val());
    return (new URLSearchParams(formData)).toString();
}

function fillFormFromResponseObject(entityObject) {
    if ('permissionId' in entityObject) {
        $("#permission-id").val(entityObject.permissionId);
    }
    if ('permission_name' in entityObject) {
        $("#permission-name").val(entityObject.permission_name);
    }
    if ('permission_description' in entityObject) {
        $("#permission-description").val(entityObject.permission_description);
    }
    if ('permission_identifier' in entityObject) {
        $("#permission-identifier").val(entityObject.permission_identifier);
    }
    if ('dateCreated' in entityObject) {
        $("#permission-date-created").val(entityObject.dateCreated);
    }
    if ('dateLastModified' in entityObject) {
        $("#permission-date-last-modified").val(entityObject.dateLastModified);
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

function loadPermission() {
    let selectedRecordId = document.getElementById("permission-selector").value;

    const options = {
        "url": `${API_PERMISSION_URL}?permissionId=${selectedRecordId}`,
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

function createPermission() {
    const options = {
        "url": `${API_PERMISSION_URL}`,
        "method": "post",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };

    $.ajax(options)
        .done((data, status, jqXHR) => {
            console.log("Received data: ", data);

            if ('name' in data) {
                let selector = document.getElementById("permission-selector");
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

function updatePermission() {
    const options = {
        "url": `${API_PERMISSION_URL}`,
        "method": "put",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };

    $.ajax(options)
        .done((data, status, jqXHR) => {
            console.log("Received data: ", data);

            // Replace the text in the selector with the updated values
            let formIdValue = document.getElementById("permission-id").value;
            if ('name' in data) {
                let selector = /** @type {HTMLSelectElement} */ document.getElementById("permission-selector");
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

function deletePermission() {
    const options = {
        "url": `${API_PERMISSION_URL}`,
        "method": "delete",
        "data": getFormDataAsUrlEncoded(),
        "dataType": "json"
    };

    $.ajax(options)
        .done((data, status, jqXHR) => {
            console.log("Received data: ", data);
            let formIdValue = document.getElementById("permission-id").value;
            if (formIdValue) {
                let selector = /** @type {HTMLSelectElement} */ document.getElementById("permission-selector");
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

document.getElementById("view-instance-button").onclick = loadPermission;
document.getElementById("clear-button").onclick = clearForm;
document.getElementById("create-button").onclick = createPermission;
document.getElementById("update-button").onclick = updatePermission;
document.getElementById("delete-button").onclick = deletePermission;
$("#form").on("change", ":input", updateClearButtonState);