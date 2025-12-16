define(['knockout'], function(ko) {
    function Product(name, rating) {
        this.name = name;
        this.userRating = ko.observable(rating || null);
    }
     
    function MyViewModel() {
        this.products = ko.observableArray(); // Start empty
    }
     
    MyViewModel.prototype.addProduct = function() {
        var name = 'Product ' + (this.products().length + 1);
        this.products.push(new Product(name));
    };

    /**
     * MyViewModel: la 1 ham.
     */
    return MyViewModel;
});