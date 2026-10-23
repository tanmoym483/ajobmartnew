
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
        >

        <!-- Bootstrap Icons -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
            rel="stylesheet"
        >
    <style>
    .welcome-register-page {
    min-height: 100vh;
    padding: 35px 12px;
    background:
        radial-gradient(circle at 50% 35%, #198754 0%, #075e42 45%, #043c2e 100%);
    background-attachment: fixed;
}

.welcome-register-card {
    max-width: 760px;
    margin: 0 auto;
    overflow: hidden;
    background: #fff;
    border: 1px solid rgba(255, 255, 255, .2);
    border-radius: 18px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, .2);
}

    .welcome-banner {
    padding: 30px 24px;
    color: #fff;
    text-align: center;
    background: linear-gradient(135deg, #075e42, #12a879);
}

    .welcome-banner .gift-icon {
        font-size: 36px;
        margin-bottom: 8px;
    }

    .welcome-banner h1 {
        margin: 0 0 8px;
        font-size: 29px;
        font-weight: 800;
    }

    .welcome-banner p {
        margin: 0;
        opacity: .95;
    }

    .welcome-register-body {
        padding: 28px;
    }

    .welcome-register-body .form-label {
        font-weight: 600;
        color: #303846;
    }

    .welcome-register-body .form-control {
        min-height: 46px;
        border-radius: 9px;
    }

    .cashback-field {
        background: #eafaf3 !important;
        color: #087f5b !important;
        font-weight: 800;
    }

    .section-heading {
        margin: 25px 0 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e9edf2;
        font-weight: 750;
        font-size: 17px;
    }

    .otp-status {
        font-size: 13px;
        margin-top: 8px;
    }

    .welcome-submit-btn {
        min-height: 49px;
        border-radius: 10px;
        font-weight: 700;
    }

    @media (max-width: 576px) {
        .welcome-register-page {
            padding: 15px 8px;
        }

        .welcome-banner {
            padding: 25px 16px;
        }

        .welcome-banner h1 {
            font-size: 24px;
        }

        .welcome-register-body {
            padding: 20px 16px;
        }
    }

    
/* Full-screen welcome cashback splash */
.cashback-splash {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 20px;
    background:
        radial-gradient(circle at 50% 35%, #198754 0%, #075e42 45%, #043c2e 100%);
    color: #fff;
    opacity: 1;
    visibility: visible;
    transition: opacity .6s ease, visibility .6s ease;
}

.cashback-splash.splash-hide {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

.splash-content {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 480px;
    text-align: center;
    animation: splashEnter .8s cubic-bezier(.2,.8,.2,1) both;
}

.splash-badge {
    display: inline-block;
    padding: 9px 17px;
    border: 1px solid rgba(255,255,255,.35);
    border-radius: 50px;
    background: rgba(255,255,255,.12);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 2px;
}

.splash-gift {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 145px;
    height: 145px;
    margin: 23px auto 10px;
    animation: giftBounce 1.2s ease-in-out infinite;
}

.splash-gift > .bi-gift-fill {
    position: relative;
    z-index: 1;
    color: #ffd54a;
    font-size: 94px;
    filter: drop-shadow(0 10px 18px rgba(0,0,0,.2));
}

.gift-glow {
    position: absolute;
    inset: 5px;
    border: 2px solid rgba(255,223,111,.55);
    border-radius: 50%;
    box-shadow: 0 0 35px rgba(255,211,71,.3);
    animation: glowPulse 1.5s ease-in-out infinite;
}

.gift-spark {
    position: absolute;
    color: #ffe68a;
    font-size: 28px;
    animation: sparkle 1.4s ease-in-out infinite;
}

.spark-one {
    top: 4px;
    right: 2px;
}

.spark-two {
    top: 25px;
    left: -2px;
    animation-delay: .4s;
}

.spark-three {
    bottom: 8px;
    right: 2px;
    animation-delay: .7s;
}

.splash-welcome {
    margin: 12px 0 5px;
    color: #c7ffdf;
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 3px;
}

.splash-title {
    margin: 0;
    font-size: clamp(26px, 6vw, 39px);
    font-weight: 900;
    letter-spacing: 2px;
}

.splash-amount {
    display: flex;
    justify-content: center;
    align-items: baseline;
    gap: 3px;
    margin: 4px 0;
    color: #ffdf59;
    font-size: clamp(75px, 17vw, 110px);
    font-weight: 1000;
    line-height: 1.15;
    text-shadow: 0 5px 0 #bb8410, 0 12px 30px rgba(0,0,0,.25);
    animation: amountPop .7s .4s both;
}

.splash-description {
    margin: 10px 0 22px;
    color: rgba(255,255,255,.9);
    font-size: 15px;
}

.claim-cashback-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    max-width: 330px;
    min-height: 56px;
    padding: 14px 24px;
    border: 0;
    border-radius: 14px;
    background: linear-gradient(135deg, #ffe879, #ffbd24);
    color: #16442f;
    box-shadow: 0 8px 25px rgba(0,0,0,.2);
    font-size: 17px;
    font-weight: 900;
    cursor: pointer;
    transition: transform .2s, box-shadow .2s;
}

.claim-cashback-btn:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 12px 30px rgba(0,0,0,.25);
}

.splash-footer {
    margin: 18px 0 0;
    color: rgba(255,255,255,.78);
    font-size: 12px;
}

.skip-splash {
    display: block;
    margin: 12px auto 0;
    padding: 6px 10px;
    border: 0;
    background: transparent;
    color: rgba(255,255,255,.8);
    font-size: 12px;
    text-decoration: underline;
    cursor: pointer;
}

.splash-confetti {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
}

.confetti-piece {
    position: absolute;
    top: -20px;
    width: 9px;
    height: 14px;
    border-radius: 2px;
    animation: confettiFall linear forwards;
}

@keyframes splashEnter {
    from { opacity: 0; transform: translateY(25px) scale(.92); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

@keyframes giftBounce {
    0%, 100% { transform: translateY(0) rotate(-3deg); }
    50%      { transform: translateY(-12px) rotate(3deg); }
}

@keyframes glowPulse {
    0%, 100% { transform: scale(.9); opacity: .5; }
    50%      { transform: scale(1.12); opacity: 1; }
}

@keyframes sparkle {
    0%, 100% { opacity: .35; transform: scale(.7) rotate(0); }
    50%      { opacity: 1; transform: scale(1.25) rotate(25deg); }
}

@keyframes amountPop {
    from { opacity: 0; transform: scale(.55); }
    to   { opacity: 1; transform: scale(1); }
}

@keyframes confettiFall {
    to { transform: translate3d(var(--drift), 110vh, 0) rotate(760deg); }
}

@media (max-width: 576px) {
    .splash-gift {
        width: 120px;
        height: 120px;
        margin-top: 20px;
    }

    .splash-gift > .bi-gift-fill {
        font-size: 78px;
    }

    .splash-welcome {
        font-size: 11px;
        letter-spacing: 2px;
    }

    .splash-description {
        font-size: 13px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .cashback-splash *,
    .cashback-splash *::before,
    .cashback-splash *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
 

 
/* =========================================
   AJOBMART REWARD / WINNING BACKGROUND
========================================= */

.welcome-register-page {
    position: relative;
    isolation: isolate;
    overflow: hidden;
    background:
        radial-gradient(ellipse at 50% 0%,
            #16865b 0%,
            #075e42 45%,
            #032e23 100%);
}

/* Background animation layer */
.reward-background {
    position: absolute;
    inset: 0;
    z-index: -1;
    overflow: hidden;
    pointer-events: none;
}

/* Moving golden spotlights */
.reward-glow {
    position: absolute;
    width: 420px;
    height: 420px;
    border-radius: 50%;
    filter: blur(75px);
    opacity: .35;
    background: #ffdf59;
    animation: rewardGlowMove 9s ease-in-out infinite alternate;
}

.reward-glow-one {
    top: 5%;
    left: -180px;
}

.reward-glow-two {
    right: -200px;
    top: 45%;
    background: #00ffc3;
    animation-delay: -4s;
}

/* Floating award stars */
.reward-orbit {
    position: absolute;
    inset: 0;
}

.reward-star {
    position: absolute;
    color: #ffe879;
    text-shadow: 0 0 12px rgba(255, 225, 90, .9);
    animation: rewardStarFloat 3s ease-in-out infinite;
}

.star-one  { top: 8%;  left: 12%; font-size: 28px; }
.star-two  { top: 25%; left: 85%; font-size: 34px; animation-delay: -1s; }
.star-three { top: 52%; left: 7%; font-size: 22px; animation-delay: -2s; }
.star-four { top: 72%; left: 91%; font-size: 30px; animation-delay: -.5s; }
.star-five { top: 88%; left: 25%; font-size: 24px; animation-delay: -1.5s; }
.star-six  { top: 40%; left: 95%; font-size: 20px; animation-delay: -2.5s; }

/* Floating cashback coins */
.reward-coin {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 43px;
    height: 43px;
    border: 2px solid #fff0a3;
    border-radius: 50%;
    color: #795000;
    font-size: 21px;
    font-weight: 900;
    background: linear-gradient(135deg, #fff5a8, #ffc928, #e89a00);
    box-shadow:
        0 0 15px rgba(255, 207, 48, .45),
        inset 0 2px 4px rgba(255,255,255,.8);
    animation: rewardCoinFloat 5s ease-in-out infinite;
}

.coin-one { top: 15%; left: 5%; }
.coin-two { top: 35%; right: 8%; animation-delay: -1.5s; }
.coin-three { top: 60%; left: 4%; animation-delay: -3s; }
.coin-four { top: 82%; right: 5%; animation-delay: -2s; }
.coin-five { top: 94%; left: 55%; animation-delay: -4s; }
.coin-six { top: 5%; right: 22%; animation-delay: -2.5s; }

/* Animated award ribbons */
.reward-ribbons span {
    position: absolute;
    top: -80px;
    width: 12px;
    height: 90px;
    border-radius: 3px;
    opacity: .65;
    background: linear-gradient(
        90deg, #ffd84c, #fff3a0, #e7a500
    );
    animation: rewardRibbonFall 8s linear infinite;
}

.reward-ribbons span:nth-child(1) {
    left: 20%;
    animation-delay: -2s;
}

.reward-ribbons span:nth-child(2) {
    left: 40%;
    animation-delay: -6s;
}

.reward-ribbons span:nth-child(3) {
    left: 70%;
    animation-delay: -4s;
}

.reward-ribbons span:nth-child(4) {
    left: 90%;
    animation-delay: -1s;
}

/* Keyframes */
@keyframes rewardGlowMove {
    0% {
        transform: translate(0, 0) scale(.8);
    }
    50% {
        transform: translate(120px, 100px) scale(1.2);
    }
    100% {
        transform: translate(40px, 200px) scale(.9);
    }
}

@keyframes rewardStarFloat {
    0%, 100% {
        transform: translateY(0) rotate(0) scale(.7);
        opacity: .35;
    }
    50% {
        transform: translateY(-25px) rotate(35deg) scale(1.25);
        opacity: 1;
    }
}

@keyframes rewardCoinFloat {
    0%, 100% {
        transform: translateY(0) rotateY(0deg);
    }
    50% {
        transform: translateY(-30px) rotateY(180deg);
    }
}

@keyframes rewardRibbonFall {
    0% {
        transform: translateY(-100px) rotate(0deg);
        opacity: 0;
    }
    15% {
        opacity: .65;
    }
    100% {
        transform: translateY(110vh) rotate(360deg);
        opacity: 0;
    }
}

/* Keep the form above the animated background */
.welcome-register-card {
    position: relative;
    z-index: 1;
}

/* Reduce animation for accessibility */
@media (prefers-reduced-motion: reduce) {
    .reward-background *,
    .reward-background *::before,
    .reward-background *::after {
        animation: none !important;
    }
}



.welcome-register-card {
    border: 2px solid rgba(255, 215, 80, .65);
    box-shadow:
        0 20px 60px rgba(0, 0, 0, .28),
        0 0 30px rgba(255, 215, 80, .12);
}

.welcome-banner {
    position: relative;
    overflow: hidden;
    background: linear-gradient(
        120deg,
        #064e3b,
        #087f5b,
        #13a878,
        #087f5b
    );
    background-size: 250% 250%;
    animation: awardHeaderShimmer 6s ease infinite;
}

@keyframes awardHeaderShimmer {
    0%, 100% { background-position: 0% 50%; }
    50%      { background-position: 100% 50%; }
}



/* Animated winning background for the registration form */
.welcome-register-page {
    position: relative;
    isolation: isolate;
    min-height: 100vh;
    padding: 35px 12px;
    background: linear-gradient(
        -45deg,
        #034b32,
        #087f5b,
        #16a085,
        #064e3b,
        #c18a14,
        #075e42
    );
    background-size: 400% 400%;
    animation: winningBackground 12s ease infinite;
}

/* Golden light shining across the whole background */
.welcome-register-page::before {
    content: "";
    position: absolute;
    inset: 0;
    z-index: -1;
    pointer-events: none;
    background:
        radial-gradient(
            circle at 15% 20%,
            rgba(255, 222, 89, .45),
            transparent 28%
        ),
        radial-gradient(
            circle at 85% 75%,
            rgba(255, 255, 255, .18),
            transparent 30%
        ),
        radial-gradient(
            circle at 50% 50%,
            rgba(0, 255, 170, .15),
            transparent 45%
        );
    background-size: 200% 200%;
    animation: goldenSpotlight 7s ease-in-out infinite alternate;
}

/* Keep the registration form above the animation */
.welcome-register-card {
    position: relative;
    z-index: 1;
    border: 2px solid rgba(255, 215, 80, .7);
    box-shadow:
        0 20px 55px rgba(0, 0, 0, .3),
        0 0 35px rgba(255, 215, 80, .2);
}

/* Smooth moving prize colours */
@keyframes winningBackground {
    0%   { background-position: 0% 50%; }
    25%  { background-position: 50% 100%; }
    50%  { background-position: 100% 50%; }
    75%  { background-position: 50% 0%; }
    100% { background-position: 0% 50%; }
}

/* Moving golden spotlight */
@keyframes goldenSpotlight {
    0% {
        opacity: .55;
        transform: scale(1);
    }
    100% {
        opacity: 1;
        transform: scale(1.08);
    }
}

/* Mobile spacing */
@media (max-width: 576px) {
    .welcome-register-page {
        padding: 15px 8px;
    }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    .welcome-register-page,
    .welcome-register-page::before {
        animation: none;
    }
}



/* ======================================
   TRANSPARENT CASHBACK REGISTRATION CARD
====================================== */

.welcome-register-card {
    position: relative;
    overflow: hidden;
    isolation: isolate;

    background: rgba(255, 255, 255, 0.10);
    border: 1px solid rgba(255, 225, 120, 0.65);
    border-radius: 22px;

    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);

    box-shadow:
        0 20px 55px rgba(0, 0, 0, 0.25),
        0 0 30px rgba(255, 215, 80, 0.15);

    animation: cardAwardGlow 4s ease-in-out infinite alternate;
}

/* Animated gold glow INSIDE the card */
.welcome-register-card::before {
    content: "";
    position: absolute;
    inset: -50%;
    z-index: -1;
    pointer-events: none;

    background: conic-gradient(
        from 0deg,
        transparent,
        rgba(255, 215, 80, 0.20),
        transparent,
        rgba(0, 255, 170, 0.15),
        transparent
    );

    animation: cardGoldenSpin 12s linear infinite;
}

/* Transparent green reward header */
.welcome-register-card .welcome-banner {
    background: linear-gradient(
        135deg,
        rgba(4, 65, 44, 0.88),
        rgba(14, 150, 100, 0.78),
        rgba(255, 193, 7, 0.32)
    );

    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

/* Glass-like form body */
.welcome-register-card .welcome-register-body {
    position: relative;
    z-index: 1;
    background: rgba(255, 255, 255, 0.12);
}

/* Readable section headings */
.welcome-register-card .section-heading {
    color: #ffffff;
    border-bottom-color: rgba(255, 255, 255, 0.25);
}

/* Form labels */
.welcome-register-card .form-label {
    color: #ffffff;
}

/* Glass input fields */
.welcome-register-card .form-control {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.13);
    border: 1px solid rgba(255, 255, 255, 0.4);
}

.welcome-register-card .form-control::placeholder {
    color: rgba(255, 255, 255, 0.72);
}

.welcome-register-card .form-control:focus {
    background: rgba(255, 255, 255, 0.20);
    border-color: #ffdf59;
    box-shadow: 0 0 0 0.2rem rgba(255, 215, 80, 0.18);
    color: #ffffff;
}

/* Keep OTP input-group buttons visible */
.welcome-register-card .input-group .btn {
    font-weight: 700;
}

/* Replace white alert backgrounds with glass */
.welcome-register-card .alert-success,
.welcome-register-card .alert-light {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.25);
}

/* Animated award glow */
@keyframes cardAwardGlow {
    from {
        box-shadow:
            0 20px 55px rgba(0, 0, 0, 0.22),
            0 0 15px rgba(255, 215, 80, 0.12);
    }
    to {
        box-shadow:
            0 20px 55px rgba(0, 0, 0, 0.30),
            0 0 35px rgba(255, 215, 80, 0.35);
    }
}

@keyframes cardGoldenSpin {
    to {
        transform: rotate(360deg);
    }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    .welcome-register-card,
    .welcome-register-card::before {
        animation: none !important;
    }
}


/* =========================================
   AJOBMART WIN BLAST CELEBRATION
========================================= */

.win-blast-splash {
    background:
        radial-gradient(ellipse at 50% 35%,
            #30218b 0%,
            #15104d 48%,
            #070726 100%);
    color: #fff;
}

.win-blast-content {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 540px;
    text-align: center;
    animation: blastEnter .75s cubic-bezier(.16, 1, .3, 1) both;
}

.win-blast-heading {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 20px;
    line-height: .9;
    transform: rotate(-3deg);
}

.win-text,
.blast-text {
    display: block;
    font-weight: 1000;
    font-style: italic;
    letter-spacing: 3px;
    -webkit-text-stroke: 1px rgba(255,255,255,.8);
}

.win-text {
    color: #ffe84d;
    font-size: clamp(65px, 13vw, 100px);
    text-shadow:
        0 5px 0 #b86a00,
        0 10px 0 #753a0b,
        0 15px 25px rgba(0,0,0,.6);
    animation: winTextPop .7s .1s both;
}

.blast-text {
    margin-top: 8px;
    color: #fff;
    font-size: clamp(48px, 10vw, 76px);
    text-shadow:
        0 5px 0 #1745c5,
        0 10px 0 #5720b9,
        0 15px 25px rgba(0,0,0,.5);
    animation: blastTextPop .7s .25s both;
}

.win-reward-label {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    gap: 9px;
    padding: 12px 20px;
    border: 2px solid #ed65ff;
    border-radius: 50px;
    background: linear-gradient(110deg, #7b19d9, #c41cdd);
    box-shadow: 0 0 25px rgba(214, 63, 255, .4);
    font-size: 13px;
    font-weight: 900;
    letter-spacing: .6px;
}

.blast-gift {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 145px;
    height: 145px;
    margin: 25px auto 15px;
    animation: blastGiftBounce 1.2s ease-in-out infinite;
}

.blast-gift > .bi-gift-fill {
    position: relative;
    z-index: 2;
    color: #ffca42;
    font-size: 100px;
    filter:
        drop-shadow(0 0 10px rgba(255, 220, 50, .8))
        drop-shadow(0 12px 15px rgba(0,0,0,.4));
}

.blast-gift-glow {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: #ffce31;
    opacity: .15;
    filter: blur(28px);
    animation: blastGlow 1.5s ease-in-out infinite alternate;
}

.blast-star {
    position: absolute;
    color: #ffe76b;
    font-size: 30px;
    text-shadow: 0 0 15px #ffbd28;
    animation: blastStar 1.4s ease-in-out infinite alternate;
}

.star-a { top: 0; left: 0; }
.star-b { top: 20px; right: -4px; animation-delay: -.4s; }
.star-c { bottom: 0; right: 5px; animation-delay: -.8s; }
.star-d { bottom: 15px; left: -8px; animation-delay: -1s; }

.blast-congratulations {
    margin: 10px 0 4px;
    color: #ffdc38;
    font-size: clamp(19px, 4.5vw, 29px);
    font-weight: 1000;
    letter-spacing: 1px;
    text-shadow: 0 3px 12px rgba(255, 186, 0, .3);
}

.blast-subtitle {
    margin: 0 0 14px;
    font-size: clamp(25px, 6vw, 39px);
    font-weight: 1000;
    letter-spacing: 1px;
}

.blast-money {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
    width: fit-content;
    max-width: 100%;
    margin: 0 auto;
    padding: 8px 32px;
    border: 4px solid #fff29a;
    border-radius: 24px;
    background: linear-gradient(
        145deg,
        #fff77a 0%,
        #ffc928 45%,
        #ff9f00 100%
    );
    color: #35147c;
    font-size: clamp(65px, 15vw, 100px);
    font-weight: 1000;
    line-height: 1.2;
    text-shadow: 0 3px 0 rgba(255,255,255,.4);
    box-shadow:
        0 0 0 5px rgba(255, 195, 36, .18),
        0 0 35px rgba(255, 195, 36, .5),
        inset 0 3px 8px rgba(255,255,255,.7);
    animation: blastMoneyPop .8s .3s both;
}

.blast-description {
    margin: 20px 0;
    color: #fff;
    font-size: 15px;
    font-weight: 650;
}

.blast-success {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    padding: 13px 22px;
    border: 2px solid #12f5a0;
    border-radius: 50px;
    background: rgba(0, 220, 130, .16);
    color: #fff;
    font-size: 16px;
    font-weight: 850;
    box-shadow: 0 0 22px rgba(0, 255, 150, .15);
}

.blast-success i {
    color: #19ff9d;
    font-size: 25px;
}

.blast-redirect {
    margin: 25px 0 0;
    color: rgba(255,255,255,.85);
    font-size: 14px;
}

.blast-redirect i {
    margin-right: 8px;
    color: #ffe76b;
    font-size: 19px;
}

/* Impact animations */

@keyframes blastEnter {
    from { opacity: 0; transform: scale(.7) translateY(35px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}

@keyframes winTextPop {
    0% { transform: scale(2.2) rotate(-10deg); opacity: 0; }
    70% { transform: scale(.9) rotate(3deg); opacity: 1; }
    100% { transform: scale(1) rotate(0); opacity: 1; }
}

@keyframes blastTextPop {
    0% { transform: scale(.3) rotate(8deg); opacity: 0; }
    70% { transform: scale(1.12) rotate(-3deg); opacity: 1; }
    100% { transform: scale(1) rotate(0); opacity: 1; }
}

@keyframes blastMoneyPop {
    0% { opacity: 0; transform: scale(.35) rotate(-8deg); }
    70% { opacity: 1; transform: scale(1.08) rotate(2deg); }
    100% { opacity: 1; transform: scale(1) rotate(0); }
}

@keyframes blastGiftBounce {
    0%, 100% { transform: translateY(0) rotate(-4deg); }
    50% { transform: translateY(-12px) rotate(4deg); }
}

@keyframes blastGlow {
    from { transform: scale(.8); opacity: .12; }
    to { transform: scale(1.2); opacity: .35; }
}

@keyframes blastStar {
    from { transform: scale(.7) rotate(-15deg); opacity: .5; }
    to { transform: scale(1.25) rotate(20deg); opacity: 1; }
}

@media (max-width: 576px) {
    .win-reward-label {
        padding: 10px 13px;
        font-size: 11px;
    }

    .blast-gift {
        width: 115px;
        height: 115px;
    }

    .blast-gift > .bi-gift-fill {
        font-size: 80px;
    }

    .blast-money {
        padding: 8px 20px;
        border-radius: 18px;
    }

    .blast-description {
        font-size: 13px;
    }

    .blast-success {
        padding: 11px 16px;
        font-size: 14px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .win-blast-content *,
    .win-blast-content *::before,
    .win-blast-content *::after {
        animation: none !important;
        transition: none !important;
    }
}
 
 
/* =========================================
   WIN BLAST — FIT EVERYTHING ON SCREEN
========================================= */

.win-blast-splash {
    padding: 12px;
    overflow-y: auto;
    overflow-x: hidden;
}

.win-blast-content {
    width: 100%;
    max-width: 540px;
    margin: auto;
    flex-shrink: 0;
    padding: 8px 0;
}

.win-blast-heading {
    margin-bottom: 12px;
}

.win-text {
    font-size: clamp(42px, 8vh, 76px);
}

.blast-text {
    margin-top: 5px;
    font-size: clamp(35px, 7vh, 60px);
}

.win-reward-label {
    padding: 8px 14px;
    font-size: clamp(10px, 1.5vh, 13px);
}

.blast-gift {
    width: clamp(65px, 13vh, 105px);
    height: clamp(65px, 13vh, 105px);
    margin: 12px auto 8px;
}

.blast-gift > .bi-gift-fill {
    font-size: clamp(55px, 10vh, 80px);
}

.blast-star {
    font-size: 20px;
}

.blast-congratulations {
    margin: 6px 0 3px;
    font-size: clamp(17px, 3.2vh, 25px);
}

.blast-subtitle {
    margin-bottom: 9px;
    font-size: clamp(22px, 4.5vh, 34px);
}

.blast-money {
    padding: 4px 24px;
    border-width: 3px;
    border-radius: 16px;
    font-size: clamp(48px, 10vh, 78px);
}

.blast-description {
    margin: 12px 0;
    font-size: clamp(12px, 1.8vh, 15px);
}

.blast-success {
    padding: 9px 17px;
    font-size: clamp(12px, 2vh, 15px);
}

.blast-success i {
    font-size: 21px;
}

.blast-redirect {
    margin: 13px 0 0;
    font-size: clamp(11px, 1.7vh, 14px);
}

/* Short desktop and laptop screens */
@media (max-height: 750px) {
    .win-blast-heading {
        margin-bottom: 8px;
    }

    .blast-gift {
        margin: 8px auto 4px;
    }

    .blast-description {
        margin: 8px 0;
    }

    .blast-redirect {
        margin-top: 9px;
    }
}

/* Mobile screens */
@media (max-width: 576px) {
    .win-blast-splash {
        padding: 10px;
    }

    .win-blast-content {
        max-width: 400px;
    }

    .win-reward-label {
        letter-spacing: 0;
    }

    .blast-money {
        padding: 5px 18px;
    }
}

/* Very short screens: allow scrolling rather than clipping */
@media (max-height: 600px) {
    .win-blast-splash {
        align-items: flex-start;
    }

    .win-blast-content {
        margin-top: 0;
        margin-bottom: 10px;
    }
}



/* WIN BLAST OVERLAY */
#cashbackSuccessSplash.win-popup-active {
    position: fixed;
    inset: 0;
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
    overflow: hidden;
    background: rgba(5, 5, 35, .88);
    backdrop-filter: blur(7px);
    -webkit-backdrop-filter: blur(7px);
    opacity: 1;
    visibility: visible;
}

#cashbackSuccessSplash.splash-hide {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

.win-popup {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 350px;
    padding: 25px 18px;
    border: 2px solid rgba(255, 222, 90, .9);
    border-radius: 25px;
    background: linear-gradient(145deg, #21145e, #47118d, #15104a);
    box-shadow: 0 0 45px rgba(190, 60, 255, .5);
    color: #fff;
    text-align: center;
    transform: scale(.15) rotate(-8deg);
    opacity: 0;
}

.win-popup-active .win-popup {
    animation: winPopupBlast .65s cubic-bezier(.16, 1.3, .3, 1) forwards;
}

.win-popup-badge {
    display: inline-block;
    padding: 8px 13px;
    border-radius: 30px;
    background: linear-gradient(90deg, #ffb800, #ffdf59);
    color: #35205c;
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 1px;
}

.win-popup-gift {
    margin: 17px 0 5px;
    color: #ffdf59;
    font-size: 48px;
    filter: drop-shadow(0 0 15px #ffbf00);
    animation: winGiftBounce .7s ease-in-out infinite alternate;
}

.win-popup-title {
    color: #fff;
    font-size: 29px;
    font-weight: 1000;
    font-style: italic;
    text-shadow: 0 3px #7223d2;
}

.win-popup-money {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    margin: 13px auto;
    padding: 5px 18px;
    border: 3px solid #fff5a1;
    border-radius: 18px;
    background: linear-gradient(135deg, #fff78a, #ffc928, #ff9e00);
    color: #35147c;
    font-size: clamp(62px, 17vw, 83px);
    font-weight: 1000;
    line-height: 1.15;
    box-shadow: 0 0 30px rgba(255, 208, 35, .65);
}

.win-popup-active .win-popup-money {
    animation: winMoneyBlast .8s .25s cubic-bezier(.16, 1.4, .3, 1) both;
}

.win-popup-message {
    margin: 12px 0;
    font-size: 14px;
    font-weight: 700;
}

.win-popup-success {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 9px 13px;
    border: 1px solid #19efa1;
    border-radius: 30px;
    background: rgba(0, 220, 130, .15);
    font-size: 12px;
    font-weight: 800;
}

.win-popup-success i {
    color: #19efa1;
    font-size: 17px;
}

.win-popup-redirect {
    margin: 15px 0 0;
    color: rgba(255,255,255,.8);
    font-size: 12px;
}

@keyframes winPopupBlast {
    0% { transform: scale(.15) rotate(-8deg); opacity: 0; }
    65% { transform: scale(1.08) rotate(2deg); opacity: 1; }
    100% { transform: scale(1) rotate(0); opacity: 1; }
}

@keyframes winMoneyBlast {
    0% { transform: scale(.1); filter: brightness(2); }
    65% { transform: scale(1.2); filter: brightness(1.5); }
    100% { transform: scale(1); filter: brightness(1); }
}

@keyframes winGiftBounce {
    from { transform: translateY(0) rotate(-7deg); }
    to { transform: translateY(-7px) rotate(7deg); }
}

@media (max-width: 380px) {
    .win-popup {
        padding: 20px 13px;
    }

    .win-popup-title {
        font-size: 25px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .win-popup,
    .win-popup * {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
    }
}


</style>
</head>
<body>
    


<!-- Ajobmart ₹100 Welcome Splash -->
<div id="cashbackSplash" class="cashback-splash">
    <div class="splash-confetti" id="splashConfetti"></div>

    <div class="splash-content">
        <div class="splash-badge">
            <i class="bi bi-stars"></i> SPECIAL WELCOME GIFT
        </div>

        <div class="splash-gift">
            <div class="gift-glow"></div>
            <i class="bi bi-gift-fill"></i>
            <span class="gift-spark spark-one">✦</span>
            <span class="gift-spark spark-two">✧</span>
            <span class="gift-spark spark-three">✦</span>
        </div>

        <p class="splash-welcome">WELCOME TO AJOBMART!</p>

        <h1 class="splash-title">
            YOU'VE WON
        </h1>

        <div class="splash-amount">
            <span>₹</span><span id="cashbackCounter">0</span>
        </div>

        <p class="splash-description">
            Your exclusive welcome cashback is waiting!
        </p>

        <button type="button" id="claimCashbackBtn"
                class="claim-cashback-btn">
            Claim My ₹100
            <i class="bi bi-arrow-right-circle-fill"></i>
        </button>

        <p class="splash-footer">
            <i class="bi bi-shield-check"></i>
            Register and verify your email to receive the offer.
        </p>

        <button type="button" id="skipSplashBtn" class="skip-splash">
            Continue to registration
        </button>
    </div>
</div>






<div id="cashbackSuccessSplash" class="cashback-splash splash-hide">
    <div id="successConfetti" class="splash-confetti"></div>

    <div class="win-popup">
        <div class="win-popup-badge">
            <i class="bi bi-stars"></i> AJOBMART WIN BLAST
        </div>

        <div class="win-popup-gift">
            <i class="bi bi-gift-fill"></i>
        </div>

        <div class="win-popup-title">YOU WON!</div>

        <div class="win-popup-money">
            <span>₹</span><span>100</span>
        </div>

        <p class="win-popup-message">
            Welcome Cashback Unlocked!
        </p>

        <div class="win-popup-success">
            <i class="bi bi-check-circle-fill"></i>
            Registration Successful
        </div>

        <p class="win-popup-redirect">
            Redirecting to Ajobmart...
        </p>
    </div>
</div>






<div class="welcome-register-page">
    <!-- Animated Reward Background -->
<div class="reward-background" aria-hidden="true">
    <div class="reward-glow reward-glow-one"></div>
    <div class="reward-glow reward-glow-two"></div>

    <div class="reward-orbit">
        <span class="reward-star star-one">✦</span>
        <span class="reward-star star-two">✧</span>
        <span class="reward-star star-three">★</span>
        <span class="reward-star star-four">✦</span>
        <span class="reward-star star-five">✧</span>
        <span class="reward-star star-six">★</span>
    </div>

    <div class="reward-coins">
        <span class="reward-coin coin-one">₹</span>
        <span class="reward-coin coin-two">₹</span>
        <span class="reward-coin coin-three">₹</span>
        <span class="reward-coin coin-four">₹</span>
        <span class="reward-coin coin-five">₹</span>
        <span class="reward-coin coin-six">₹</span>
    </div>
    
<div class="reward-coins">
    <span class="reward-coin coin-one">₹100</span>
    <span class="reward-coin coin-two">₹100</span>
    <span class="reward-coin coin-three">₹100</span>
    <span class="reward-coin coin-four">₹100</span>
    <span class="reward-coin coin-five">₹100</span>
    <span class="reward-coin coin-six">₹100</span>
</div>


    <div class="reward-ribbons">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
    <div class="welcome-register-card">

        <div class="welcome-banner">
            <div class="gift-icon">🎁</div>
            <h1>Welcome to Ajobmart!</h1>
            <p>Register today and receive ₹100 welcome cashback.</p>
        </div>
<div class="welcome-register-body">

    
<form id="welcomeRegistrationForm" method="post" novalidate>
    <?= csrf_field() ?>

    <div class="alert alert-success" role="alert">
        <strong>🎁 ₹100 Welcome Cashback</strong>
        <div class="small mt-1">
            Complete registration and verify your email to receive
                    your welcome cashback in your profile.
        </div>
    </div>

    <h5 class="section-heading">1. Your details</h5>

    <div class="mb-3">
        <label for="name" class="form-label">Full name *</label>
        <input type="text"
               id="name"
               name="name"
               class="form-control"
               placeholder="Enter your full name"
               maxlength="150"
               autocomplete="name"
               required>
    </div>

    <div class="mb-3">
        <label for="mobile" class="form-label">Mobile number *</label>
        <input type="tel"
               id="mobile"
               name="mobile"
               class="form-control"
               placeholder="10-digit mobile number"
               inputmode="numeric"
               pattern="[0-9]{10}"
               maxlength="10"
               autocomplete="tel"
               required>
    </div>

    <h5 class="section-heading">2. Verify your email</h5>

    
<div class="mb-3">
    <label for="email" class="form-label">Email address *</label>

    <input type="email"
           id="email"
           name="email"
           class="form-control"
           placeholder="Enter your email address"
           maxlength="190"
           autocomplete="email"
           required>

    <div id="otpSendStatus" class="otp-status" aria-live="polite"></div>
</div>

<div class="mb-3" id="otpSection" hidden>
    <label for="otp" class="form-label">Email verification code *</label>

    <input type="text"
           id="otp"
           name="otp"
           class="form-control"
           placeholder="Enter 6-digit OTP"
           inputmode="numeric"
           autocomplete="one-time-code"
           pattern="[0-9]{6}"
           maxlength="6"
           required>

    <div id="otpVerifyStatus" class="otp-status" aria-live="polite"></div>
</div>


    <div class="alert alert-light border small mb-0">
        <i class="bi bi-shield-lock text-success"></i>
        Your account will be created automatically after successful
        OTP verification. A random password will be generated securely
        by our system.
    </div>
</form>

</div>
</div>
</div>


<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
$(function () {
    const splash = document.getElementById('cashbackSplash');
    if (!splash) return;

    const counter = document.getElementById('cashbackCounter');
    const confettiContainer = document.getElementById('splashConfetti');
    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    ).matches;

    let closed = false;
    let countFrame;

    // Create falling confetti.
    function createConfetti() {
        if (reducedMotion) return;

        const colors = [
            '#ffdf59', '#ffffff', '#ff8c69',
            '#65e6b4', '#8fc7ff', '#ff83c8'
        ];

        for (let i = 0; i < 85; i++) {
            const piece = document.createElement('span');
            piece.className = 'confetti-piece';
            piece.style.left = Math.random() * 100 + '%';
            piece.style.backgroundColor =
                colors[Math.floor(Math.random() * colors.length)];
            piece.style.animationDuration =
                (2.5 + Math.random() * 3) + 's';
            piece.style.animationDelay =
                (Math.random() * 1.8) + 's';
            piece.style.setProperty(
                '--drift',
                (Math.random() * 180 - 90) + 'px'
            );

            confettiContainer.appendChild(piece);
        }
    }

    // Animate the amount from ₹0 to ₹100.
    function animateCashback() {
        if (reducedMotion) {
            counter.textContent = '100';
            return;
        }

        const start = performance.now();
        const duration = 1600;

        function update(now) {
            if (closed) return;

            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 4);

            counter.textContent = Math.floor(eased * 100);

            if (progress < 1) {
                countFrame = requestAnimationFrame(update);
            } else {
                counter.textContent = '100';
            }
        }

        countFrame = requestAnimationFrame(update);
    }

    // Reveal the registration form.
    function closeSplash() {
        if (closed) return;
        closed = true;

        if (countFrame) {
            cancelAnimationFrame(countFrame);
        }

        splash.classList.add('splash-hide');

        document.body.style.overflow = '';

        window.setTimeout(function () {
            splash.remove();
        }, reducedMotion ? 50 : 650);
    }

    createConfetti();
    animateCashback();

    document.body.style.overflow = 'hidden';

    document.getElementById('claimCashbackBtn')
        .addEventListener('click', closeSplash);

    document.getElementById('skipSplashBtn')
        .addEventListener('click', closeSplash);

    // Automatically reveal the form after 8 seconds.
    window.setTimeout(closeSplash, 8000);
}); 



$(function () {
    const sendOtpUrl = <?= json_encode(site_url('welcome-registration/send-otp')) ?>;
    const verifyOtpUrl = <?= json_encode(site_url('welcome-registration/verify-otp')) ?>;
    const registerUrl = <?= json_encode(site_url('welcome-registration/register')) ?>;
    const homeUrl = <?= json_encode(site_url('/')) ?>;

    let emailTimer = null;
    let lastOtpEmail = '';
    let otpRequestInProgress = false;
    let otpVerificationInProgress = false;
    let registrationInProgress = false;
    let verifiedEmail = '';

    const $email = $('#email');
    const $otp = $('#otp');
    const $otpSection = $('#otpSection');

    function showMessage(selector, message, type = 'text-muted') {
        $(selector)
            .removeClass('text-success text-danger text-muted')
            .addClass(type)
            .text(message);
    }

    function csrfData() {
        const token = $('input[name="<?= csrf_token() ?>"]').val();

        return token
            ? { ['<?= csrf_token() ?>']: token }
            : {};
    }

    function updateCsrf(response) {
        if (response && response.csrfHash) {
            $('input[name="<?= csrf_token() ?>"]')
                .val(response.csrfHash);
        }
    }

    function ajaxError(xhr, selector) {
        const response = xhr.responseJSON || {};

        updateCsrf(response);

        showMessage(
            selector,
            response.message || 'Something went wrong. Please try again.',
            'text-danger'
        );
    }

    function resetVerification() {
        verifiedEmail = '';
        lastOtpEmail = '';

        $otp.val('');
        $otpSection.prop('hidden', true);

        showMessage('#otpVerifyStatus', '');
    }

    // Send OTP automatically when the email is valid.
    function scheduleOtpSend() {
        clearTimeout(emailTimer);

        const email = $.trim($email.val()).toLowerCase();

        if (!$email[0].checkValidity() || !email) {
            return;
        }

        if (email === lastOtpEmail || otpRequestInProgress) {
            return;
        }

        emailTimer = setTimeout(function () {
            sendOtp(email);
        }, 800);
    }

    function sendOtp(email) {
        if (otpRequestInProgress || registrationInProgress) {
            return;
        }

        otpRequestInProgress = true;

        showMessage(
            '#otpSendStatus',
            'Sending verification code...',
            'text-muted'
        );

        $.ajax({
            url: sendOtpUrl,
            type: 'POST',
            dataType: 'json',
            data: Object.assign({ email: email }, csrfData())
        })
        .done(function (response) {
            updateCsrf(response);

            if (!response.status) {
                showMessage(
                    '#otpSendStatus',
                    response.message || 'Unable to send OTP.',
                    'text-danger'
                );
                return;
            }

            lastOtpEmail = email;
            $otpSection.prop('hidden', false);

            showMessage(
                '#otpSendStatus',
                response.message || 'OTP sent. Check your email.',
                'text-success'
            );

            $otp.trigger('focus');
        })
        .fail(function (xhr) {
            ajaxError(xhr, '#otpSendStatus');
        })
        .always(function () {
            otpRequestInProgress = false;
        });
    }

    $email.on('input', function () {
        const email = $.trim(this.value).toLowerCase();

        if (email !== verifiedEmail) {
            resetVerification();
        }

        if (email !== lastOtpEmail) {
            showMessage('#otpSendStatus', '');
        }

        scheduleOtpSend();
    });

    // Only numeric OTP characters are accepted.
    $otp.on('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 6);

        if (this.value.length === 6) {
            verifyAndRegister();
        }
    });

    function verifyAndRegister() {
        if (otpVerificationInProgress || registrationInProgress) {
            return;
        }

        const form = document.getElementById('welcomeRegistrationForm');

        const name = $.trim($('#name').val());
        const mobile = $.trim($('#mobile').val());
        const email = $.trim($email.val()).toLowerCase();
        const otp = $.trim($otp.val());

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        if (!/^[0-9]{10}$/.test(mobile)) {
            showMessage(
                '#otpVerifyStatus',
                'Enter a valid 10-digit mobile number.',
                'text-danger'
            );
            $('#mobile').trigger('focus');
            return;
        }

        if (otp.length !== 6 || email !== lastOtpEmail) {
            return;
        }

        otpVerificationInProgress = true;

        showMessage(
            '#otpVerifyStatus',
            'Verifying your OTP...',
            'text-muted'
        );

        $.ajax({
            url: verifyOtpUrl,
            type: 'POST',
            dataType: 'json',
            data: Object.assign({
                email: email,
                otp: otp
            }, csrfData())
        })
        .done(function (response) {
            updateCsrf(response);

            if (!response.status) {
                showMessage(
                    '#otpVerifyStatus',
                    response.message || 'Invalid or expired OTP.',
                    'text-danger'
                );

                $otp.val('');
                $otp.trigger('focus');
                return;
            }

            verifiedEmail = email;
            registrationInProgress = true;

            showMessage(
                '#otpVerifyStatus',
                'OTP verified. Creating your account...',
                'text-success'
            );

            createAccount(name, mobile, email, otp);
        })
        .fail(function (xhr) {
            ajaxError(xhr, '#otpVerifyStatus');
        })
        .always(function () {
            otpVerificationInProgress = false;
        });
    }

    function createAccount(name, mobile, email, otp) {
        $.ajax({
            url: registerUrl,
            type: 'POST',
            dataType: 'json',
            data: Object.assign({
                name: name,
                mobile: mobile,
                email: email,
                otp: otp
            }, csrfData())
        })
        .done(function (response) {
            updateCsrf(response);

            if (!response.status) {
                registrationInProgress = false;

                showMessage(
                    '#otpVerifyStatus',
                    response.message || 'Registration failed.',
                    'text-danger'
                );
                return;
            }

            // Show the reward only after confirmed registration.
            showSuccessCelebration();
        })
        .fail(function (xhr) {
            registrationInProgress = false;
            ajaxError(xhr, '#otpVerifyStatus');
        });
    }

    
function showSuccessCelebration() {
    const splash = document.getElementById('cashbackSuccessSplash');

    if (!splash) {
        window.location.replace(homeUrl);
        return;
    }

    splash.classList.remove('splash-hide');
    splash.classList.add('win-popup-active');

    document.body.style.overflow = 'hidden';

    const confetti = document.getElementById('successConfetti');

    if (confetti && !window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    ).matches) {
        const colors = [
            '#ffdf59', '#ff42d0', '#55e8ff',
            '#ffffff', '#7dff9b'
        ];

        for (let i = 0; i < 55; i++) {
            const piece = document.createElement('span');

            piece.className = 'confetti-piece';
            piece.style.left = Math.random() * 100 + '%';
            piece.style.backgroundColor =
                colors[Math.floor(Math.random() * colors.length)];

            piece.style.animationDuration =
                (1.5 + Math.random() * 1.5) + 's';

            piece.style.animationDelay =
                (Math.random() * .5) + 's';

            piece.style.setProperty(
                '--drift',
                (Math.random() * 160 - 80) + 'px'
            );

            confetti.appendChild(piece);
        }
    }

    setTimeout(function () {
        window.location.replace(homeUrl);
    }, 1000);
}


    $('#welcomeRegistrationForm').on('submit', function (event) {
        event.preventDefault();
    });
});


</script>
</body>
</html>