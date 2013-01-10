(function () {

    Markdown.qaFileUpload = function(uploadURL) {

        // A reference to this, for closures
        var that = this;

        // This is the method that is expected by the Markdown editor.
        // It has to execute "callback" when finished to tell the editor to display the file
        this.prompt = function(callback) {
            that.callback = callback;
            that.$template = $('' +
                '<div class="wmd-prompt-dialog" style="position: fixed; width: 400px; z-index: 1001; top: 50%; left: 50%; display: block; margin-top: -60.5px; margin-left: -201px;">' +
                '<div style="padding: 5px;">' +
                '	<h3 style="margin-top:0">Chose an image.</h3>' +
                '</div>' +
                '<form enctype="multipart/form-data" method="post" action="' + uploadURL + '" style="padding: 0px; margin: 0px; float: left; width: 100%; text-align: center; position: relative;">' +
                '	<label class="radio">' +
                '	  <input type="radio" name="input-type" id="input-type-file" value="file" checked>' +
                '	  From my computer' +
                '	</label><br/><br/>' +
                '   <input type="file" id="upload-file-input" name="upload-file-input" value="Upload" />' +
                '	<h3>OR</h3>' +
                '	<label class="radio">' +
                '	  <input type="radio" name="input-type" id="input-type-url" value="url">' +
                '	  From the web' +
                '	</label><br/><br/>' +
                '	<input type="text" id="url-input" placeholder="Enter file URL" style="display: block; width: 80%; margin-right: auto; margin-left: auto;">' +
                '<input class="btn-ok" type="button" value="OK" style="margin: 10px; display: inline; width: 7em;">' +
                '<input class="btn-cancel" type="button" value="Cancel" style="margin: 10px; display: inline; width: 7em;">' +
                '</form>' +
                '</div>');

            that.$template
                .appendTo($('body'))
            ;

            that.$template.find('.btn-cancel').click(function() {
                that.close(null);
            });

            that.$template.find('.btn-ok').click(function() {
                that.submit()
            });


            return true;
        };

        this.submit = function() {
            // Check if the user chose to submit afile
            if( this.$template.find('input[name=input-type]:checked').val() === "file") {
                this.uploadFile()
            } else {
                // User entered an URL

                // Clean the user's text input
                var url = this.$template.find("#url-input").val();
                url = url.replace(/^http:\/\/(https?|ftp):\/\//, '$1://');
                if (!/^(?:https?|ftp):\/\//.test(url))
                    url = 'http://' + url;

                this.close(url);
            }
        }

        this.uploadFile = function() {
            // Remove the previous iframe, to avoid confusion
            if(this.$frame)
                this.$frame.remove();

            // Create a new iframe that will be set as the form's target to have an asynchronous upload
            this.$frame = $('<iframe style="display: none" src="about:blank" name="'+'f' + Math.floor(Math.random() * 99999)+'" ></iframe>')
                .appendTo(this.$template);

            // Set the form's target to the iframe and sumbit it
            this.$template.find('form')
                .attr('target', this.$frame.attr('name'))
                .submit()
            ;
        };

        // This is called by the iframe to tell about success or failure
        window.qaFileUploadCallBack = function(success, url, message) {
            if(success) {
                that.close(url);
            } else {
                alert(message)
            }
        }

        // Closes the popup and load the image URL (if null, the editor won't process it)
        this.close = function(imageUrl) {
            this.$template.remove();
            this.callback(imageUrl);
        };
    }
})();
