document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".coupon_copy").forEach(btn => {
        btn.addEventListener("click", function() {
            navigator.clipboard.writeText(this.dataset.egaCoupon)
                .then(() => alert("Đã sao chép mã: " + this.dataset.egaCoupon));
        });
    });
});