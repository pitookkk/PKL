import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// --- Define the product variation logic globally ---
window.productVariationSelector = function(variations, basePrice, baseStock) {
    return {
        variations: variations,
        basePrice: parseFloat(basePrice),
        baseStock: parseInt(baseStock),
        selectedVariation: null,
        currentPrice: parseFloat(basePrice),
        currentStock: 'N/A', // Default stock text

        init() {
            // Set initial stock text
            this.currentStock = this.variations.length > 0 ? 'Select an option' : this.baseStock;

            // If there's only one variation, select it by default
            if(this.variations.length === 1) {
                this.selectedVariation = this.variations[0].id;
                this.updatePrice();
            }
        },

        updatePrice() {
            if (this.selectedVariation) {
                const selected = this.variations.find(v => v.id == this.selectedVariation);
                if (selected) {
                    this.currentPrice = this.basePrice + parseFloat(selected.additional_price);
                    this.currentStock = selected.stock;
                }
            } else {
                this.currentPrice = this.basePrice;
                this.currentStock = this.variations.length > 0 ? 'Select an option' : this.baseStock;
            }
        },
        
        canAddToCart() {
            if (this.variations.length > 0) {
                return this.selectedVariation !== null && this.currentStock > 0;
            }
            return this.currentStock > 0;
        },

        formatPrice(price) {
            if(isNaN(price)) return '';
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(price);
        }
    }
};

Alpine.start();
