
webhookButton = document.getElementById("webhook-button");
webhookInput = document.getElementById("webhook-input");
link = document.getElementById("link-submit").href;
metaCSRF = document.querySelector('meta[name="csrf-token"');
errorMsg = document.getElementById("error-msg");

webhookButton.addEventListener("click", () => {

    if(!webhookInput.value.trim()) {
        webhookInput.value = "";
        return;
    }

    let csrfToken = metaCSRF.getAttribute('content');
    let headers = new Headers();
    headers.append('X-CSRF-TOKEN', csrfToken);
    headers.append('Content-Type', 'application/json');

    fetch(link, {
        method: 'post',
        headers: headers,
        body: JSON.stringify({
            webhook: webhookInput.value.trim()
        })
    }).then(function(response) {
        return response.json();
    }).then(function(data) {
        if(data.status === "success") {
            console.log("Success: "+data.msg);
            errorMsg.className = '';
            errorMsg.classList.add('success');
            errorMsg.innerText=data.msg;
        }else{
            console.log("Error: "+data.msg);
            errorMsg.className = '';
            errorMsg.classList.add('error');
            errorMsg.innerText="Error: "+data.msg;
        }

        webhookInput.value = "";
    });
});
