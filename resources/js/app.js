import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// --- Define the product variation logic globally ---
window.productVariationSelector = function(product) {
    return {
        // Product Data
        variations: product.variations || [],
        basePrice: parseFloat(product.base_price),
        baseStock: parseInt(product.stock),

        // Flash Sale Data
        isFlashSaleActive: product.is_flash_sale_active,
        flashSalePrice: parseFloat(product.flash_sale_price),
        flashSaleEnd: product.flash_sale_end, // ISO 8601 date string
        countdown: '00:00:00',
        
        // State
        selectedVariation: null,
        currentPrice: parseFloat(product.current_price),
        currentStock: 'N/A',

        init() {
            // Set initial stock text
            this.currentStock = this.variations.length > 0 ? 'Select an option' : this.baseStock;

            // If there's only one variation, select it by default
            if(this.variations.length === 1) {
                this.selectedVariation = this.variations[0].id;
            }
            
            // Initial price calculation
            this.updatePrice();

            // Setup countdown timer if flash sale is active
            if (this.isFlashSaleActive) {
                this.calculateCountdown(); // Initial call
                const timer = setInterval(() => {
                    this.calculateCountdown();
                    // Stop timer if sale ends
                    if (!this.isFlashSaleActive) {
                        clearInterval(timer);
                        this.updatePrice(); // Recalculate price based on new state
                    }
                }, 1000);
            }
        },

        updatePrice() {
            const base = this.isFlashSaleActive ? this.flashSalePrice : this.basePrice;
            let finalPrice = base;
            let stock = this.baseStock;

            if (this.selectedVariation) {
                const selected = this.variations.find(v => v.id == this.selectedVariation);
                if (selected) {
                    finalPrice += parseFloat(selected.additional_price);
                    stock = selected.stock;
                }
            }
            
            this.currentPrice = finalPrice;
            this.currentStock = this.variations.length > 0 && !this.selectedVariation ? 'Select an option' : stock;
        },

        calculateCountdown() {
            const endDate = new Date(this.flashSaleEnd).getTime();
            const now = new Date().getTime();
            const distance = endDate - now;

            if (distance < 0) {
                this.countdown = '00:00:00';
                this.isFlashSaleActive = false; // The sale has ended
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
            const seconds = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0');
            
            this.countdown = `${hours}:${minutes}:${seconds}`;
        },
        
        canAddToCart() {
            const stock = this.currentStock;
            if (this.variations.length > 0) {
                return this.selectedVariation !== null && stock > 0;
            }
            return stock > 0;
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
