if (typeof OrderReviewController === 'object') {
    OrderReviewController.prototype._bindElementChange = OrderReviewController.prototype._bindElementChange.wrap(function (parentMethod, input) {
        if (typeof input === 'object' && typeof input.id === 'string' && input.id.substr(0, 9) === 'agreement') {
            return;
        }
        parentMethod(input);
    });
}
