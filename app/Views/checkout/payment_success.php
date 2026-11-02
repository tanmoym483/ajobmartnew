
<div class="aj-success-page">

    <div class="aj-confetti-container" id="ajConfetti"></div>

    <div class="aj-success-content">

        <!-- Animated Success Icon -->
        <div class="aj-success-icon">
            <div class="aj-success-circle">
                <i class="bi bi-check-lg"></i>
            </div>
        </div>

        <!-- Success Heading -->
        <h1 class="aj-success-title">
            Payment Successful!
        </h1>

        <p class="aj-success-subtitle">
            Congratulations! Your payment has been completed successfully.
        </p>

        <!-- Celebration Message -->
        <div class="aj-celebration-message">
            <span class="aj-party-emoji">🎉</span>
            <h3>Thank You for Shopping with Ajobmart!</h3>
            <p>
                Your order is confirmed. We're excited to get your products
                delivered to your doorstep.
            </p>
        </div>

        <!-- Loading / Redirect Animation -->
        <div class="aj-order-loading" id="ajOrderLoading">
            <div class="aj-loading-spinner"></div>
            <span>Preparing your order details...</span>
        </div>

        <!-- View Orders Button: Initially Hidden -->
        <div class="aj-order-button" id="ajOrderButton">

            <a href="<?= site_url('my-orders') ?>"
               class="aj-view-orders-btn">

                <i class="bi bi-bag-check-fill me-2"></i>
                View My Orders
                <i class="bi bi-arrow-right ms-2"></i>

            </a>

            <p class="aj-order-note">
                Your shopping journey continues with Ajobmart ❤️
            </p>

        </div>

    </div>
</div>

<style>
.aj-success-page {
    min-height: 75vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 50px 15px;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #f0fff7, #ffffff, #eff6ff);
}

.aj-success-content {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 620px;
    text-align: center;
    animation: ajContentEnter .8s ease both;
}

@keyframes ajContentEnter {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Success icon */

.aj-success-icon {
    display: flex;
    justify-content: center;
    margin-bottom: 25px;
}

.aj-success-circle {
    width: 115px;
    height: 115px;
    border-radius: 50%;
    background: #16a34a;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 65px;
    box-shadow: 0 0 0 12px rgba(22, 163, 74, .10),
                0 0 45px rgba(22, 163, 74, .30);
    animation: ajSuccessPop .8s cubic-bezier(.18,.89,.32,1.28);
}

.aj-success-circle i {
    animation: ajCheckAppear .5s .3s ease both;
}

@keyframes ajSuccessPop {
    0% {
        transform: scale(0);
    }
    70% {
        transform: scale(1.15);
    }
    100% {
        transform: scale(1);
    }
}

@keyframes ajCheckAppear {
    from {
        opacity: 0;
        transform: scale(.3);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Heading */

.aj-success-title {
    font-size: clamp(28px, 5vw, 38px);
    font-weight: 800;
    color: #14532d;
    margin-bottom: 12px;
}

.aj-success-subtitle {
    font-size: 16px;
    color: #64748b;
    line-height: 1.7;
}

/* Celebration */

.aj-celebration-message {
    background: white;
    border: 1px solid #dcfce7;
    border-radius: 20px;
    padding: 25px 20px;
    margin: 30px 0;
    box-shadow: 0 12px 35px rgba(0,0,0,.05);
}

.aj-party-emoji {
    display: inline-block;
    font-size: 42px;
    animation: ajPartyBounce 1.2s infinite;
}

@keyframes ajPartyBounce {
    0%, 100% { transform: translateY(0) rotate(0); }
    50% { transform: translateY(-10px) rotate(12deg); }
}

.aj-celebration-message h3 {
    font-size: 20px;
    font-weight: 750;
    color: #166534;
    margin: 12px 0;
}

.aj-celebration-message p {
    color: #64748b;
    font-size: 14px;
    line-height: 1.7;
    margin: 0;
}

/* Loading */

.aj-order-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    color: #64748b;
    font-size: 13px;
}

.aj-loading-spinner {
    width: 28px;
    height: 28px;
    border: 3px solid #dcfce7;
    border-top-color: #16a34a;
    border-radius: 50%;
    animation: ajSpin .8s linear infinite;
}

@keyframes ajSpin {
    to { transform: rotate(360deg); }
}

/* Orders button */

.aj-order-button {
    display: none;
    opacity: 0;
    transform: translateY(15px);
}

.aj-order-button.aj-show {
    display: block;
    animation: ajButtonReveal .7s ease forwards;
}

@keyframes ajButtonReveal {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.aj-view-orders-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 15px 35px;
    background: #16a34a;
    color: white !important;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    font-size: 16px;
    box-shadow: 0 8px 22px rgba(22,163,74,.25);
    transition: .3s ease;
}

.aj-view-orders-btn:hover {
    background: #15803d;
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(22,163,74,.35);
}

.aj-order-note {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 15px;
}

/* Confetti */

.aj-confetti-container {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
    z-index: 1;
}

.aj-confetti {
    position: absolute;
    top: -15px;
    width: 9px;
    height: 14px;
    opacity: .9;
    animation: ajConfettiFall linear forwards;
}

@keyframes ajConfettiFall {
    0% {
        transform: translateY(-20px) rotate(0deg);
        opacity: 1;
    }
    100% {
        transform: translateY(100vh) rotate(720deg);
        opacity: 0;
    }
}

@media (max-width: 576px) {
    .aj-success-page {
        min-height: 80vh;
        padding: 35px 15px;
    }

    .aj-success-circle {
        width: 95px;
        height: 95px;
        font-size: 55px;
    }

    .aj-celebration-message {
        padding: 22px 15px;
    }

    .aj-view-orders-btn {
        width: 100%;
    }
}

@media (prefers-reduced-motion: reduce) {
    .aj-success-content,
    .aj-success-circle,
    .aj-success-circle i,
    .aj-party-emoji,
    .aj-loading-spinner,
    .aj-confetti {
        animation: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const loading = document.getElementById('ajOrderLoading');
    const button = document.getElementById('ajOrderButton');
    const confettiContainer = document.getElementById('ajConfetti');

    // Create celebration confetti
    function createConfetti() {

        const colors = [
            '#16a34a',
            '#facc15',
            '#3b82f6',
            '#ec4899',
            '#f97316',
            '#a855f7'
        ];

        for (let i = 0; i < 90; i++) {

            const piece = document.createElement('span');

            piece.className = 'aj-confetti';

            piece.style.left = Math.random() * 100 + '%';
            piece.style.backgroundColor =
                colors[Math.floor(Math.random() * colors.length)];

            piece.style.animationDuration =
                (2.5 + Math.random() * 2) + 's';

            piece.style.animationDelay =
                (Math.random() * 1.5) + 's';

            piece.style.transform =
                'rotate(' + Math.random() * 360 + 'deg)';

            confettiContainer.appendChild(piece);
        }
    }

    createConfetti();

    // Reveal View My Orders after 4 seconds
    setTimeout(function () {

        loading.style.display = 'none';

        button.classList.add('aj-show');

    }, 4000);

});
</script>
