const loading = {
    start: (title = null, msg = null, fixed = false) => {
        Swal.fire({
            title: title,
            text: msg,
            // imageUrl: 'assets/images/logo_warna.png',
            // imageWidth: 100,
            // imageHeight: 100,
            allowOutsideClick: fixed,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading()
            }
        })
    },
    stop: () => {
        Swal.close()
    },
    update: (title = null, msg = null, fixed = false) => {
        if (Swal.isVisible()) {
            Swal.update({
                title: title,
                text: msg,
                allowOutsideClick: fixed,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        }
    }
}

const notif = {
    success: (message = '', title = 'success') => {
        Swal.fire(title, message, 'success')
    },
    error: (message = '', title = 'error') => {
        Swal.fire(title, message, 'error')
    },
    warning: (message = '', title = 'warning') => {
        Swal.fire(title, message, 'warning')
    },
    info: (message = '', title = 'info') => {
        Swal.fire(title, message, 'info')
    },
    question: (message = '', title = 'question', callback) => {
        Swal.fire({
            title: title,
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            callback(result)
        })
    },
    html: (params) => {
        Swal.fire(params)
    },
    toast: (message = '', duration = 'short', position = 'bottom') => {
        /*
            duration: 'short', 'long', '3000', 900 (the latter are milliseconds)
            position: 'top', 'center', 'bottom'
        */
        document.addEventListener('deviceready', function() {
            window.plugins.toast.show(message, duration, position)
        }, false)
    }
}

let looping_key = 0;

const helper = {
    caesar_chiper:  (plaintext, shiftAmount) => {
        var ciphertext = "";
        for (var i = 0; i < plaintext.length; i++) {
            var plainCharacter = plaintext.charCodeAt(i);
            if (plainCharacter >= 97 && plainCharacter <= 122) {
                ciphertext += String.fromCharCode((plainCharacter - 97 + shiftAmount) % 26 + 97);
            } else if (plainCharacter >= 65 && plainCharacter <= 90) {
                ciphertext += String.fromCharCode((plainCharacter - 65 + shiftAmount) % 26 + 65);
            } else {
                ciphertext += String.fromCharCode(plainCharacter);
            }
        }
        return ciphertext;
    },
    gen_pass_numb: () => {
        return Math.floor(Math.random() * (20)) + 1;
        // return 4;
    },
    gen_pass_string: (length) => {
        let result = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz01234567890';
        const charactersLength = characters.length;
        let counter = 0;
        while (counter < length) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            counter += 1;
        }
        return result;
    },
    gen_pass_hrf: (length) => {
        let result = '';
        const characters = 'abcdefghijklmnopqrstuvwxyz';
        const charactersLength = characters.length;
        let counter = 0;
        while (counter < length) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            counter += 1;
        }
        return result;
    },
    generate_key_enc: function() {
        var data = [];
        for (var i = 0; i < 100; i++) {
            data.push(this.gen_pass_numb());
        }
        return data;
    },
    set_varchar_key: function(keyval) {
        var res = '';
        for (var i = 0; i < keyval.length; i++) {
            if (i > 0) {
                res += this.gen_pass_hrf(1);
            }
            res += keyval[i];
            if (keyval.length == (i + 1)) {
                res += this.gen_pass_hrf(1);
            }
        }
        return res;
    },
    get_key_arr: function() {
        if (looping_key > 99) {
            looping_key = 0;
        }
        var res = looping_key;
        looping_key += parseInt(1);
        return res;
    },
    repair: function(ciphertext, shiftAmount) {
        var plaintext = "";
        for (var i = 0; i < ciphertext.length; i++) {
            var cipherCharacter = ciphertext.charCodeAt(i);
            if (cipherCharacter >= 97 && cipherCharacter <= 122) {
                plaintext += String.fromCharCode((cipherCharacter - 97 - shiftAmount + 26) % 26 + 97);
            } else if (cipherCharacter >= 65 && cipherCharacter <= 90) {
                plaintext += String.fromCharCode((cipherCharacter - 65 - shiftAmount + 26) % 26 + 65);
            } else {
                plaintext += String.fromCharCode(cipherCharacter);
            }
        }
        return plaintext;
    },
    youtube_title: url => {
        $.get(`https://noembed.com/embed?dataType=json&url=${url}`, data => {
            data = JSON.parse(data);
            return data.title;
        });
    }
}
