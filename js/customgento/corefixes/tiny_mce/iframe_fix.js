tinyMceWysiwygSetup.prototype.initialize = tinyMceWysiwygSetup.prototype.initialize.wrap(function (parentMethod, htmlId, config) {
    parentMethod(htmlId, config);
    if (typeof tinymce === 'object' && typeof tinymce.util === 'object' && typeof tinymce.util.JSON === 'object') {
        // make sure that JSON is globally available, because it is used in js/tiny_mce/plugins/media/editor_plugin.js
        window.JSON = Object.assign(window.JSON, tinymce.util.JSON);
    }
});
