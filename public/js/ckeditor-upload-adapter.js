class MyUploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file
            .then(file => new Promise((resolve, reject) => {
                const formData = new FormData();
                formData.append('upload', file);

                fetch('/ckeditor/upload', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(response => {
                    if (response.url) {
                        resolve({
                            default: response.url
                        });
                    } else {
                        reject(response.error || 'Upload failed');
                    }
                })
                .catch(error => {
                    reject('Upload failed: ' + error);
                });
            }));
    }

    abort() {
        // Abort upload process
    }
}

function MyCustomUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        return new MyUploadAdapter(loader);
    };
} 