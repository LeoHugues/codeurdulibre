window.onload = function () {
    if (window.CKEDITOR){
        var path = '/ckfinder/connector';
        CKFinder.config( { connectorPath: (window.location.pathname.indexOf("index.php") == -1 ) ? path : '/index.php'+path} );
        for (var ckInstance in CKEDITOR.instances){
            CKFinder.setupCKEditor(CKEDITOR.instances[ckInstance]);
        }
    }
}