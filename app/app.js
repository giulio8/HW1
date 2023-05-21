function onSuccess(resp) {
    console.log("onSuccess");
    //console.log(resp.status);
    if (resp.ok === false) {
        console.log("Problem with the request");
        throw resp.json();
    }
    else {
        return resp.json();
    }
}

function onError(error) {
    console.log('Error: ' + error);
}