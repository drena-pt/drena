function api(api_url, api_data, api_processData=true, api_contentType) {
    var jqXHR = $.ajax({
        url: 'api/'+api_url+'.php',
        type: 'post',
        data: api_data,
        contentType: api_contentType,
        processData: api_processData,
        async: false,
        beforeSend: function(xhr) {
            xhr.setRequestHeader ('Authorization', Cookies.get('drena_token'));
        },
        error: function (jqXHR, exception) {
            if (jqXHR.status === 0) {
                err = 'Not connect. Verify Network.';
            } else if (exception === 'parsererror') {
                err = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                err = 'Time out error.';
            } else if (exception === 'abort') {
                err = 'Ajax request aborted.';
            } else {
                err = 'Uncaught Error.' + jqXHR.responseText;
            }
            if (err){console.error(err);}
        }
    });
    var result = JSON.parse(jqXHR.responseText);
    if (result['err']){
        alert(result['err']);
    } else {
        return result;
    }
}