importScripts('https://www.gstatic.com/firebasejs/9.2.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.2.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyCy4l8zoERjZqOCbAKzGIvm5tGKMDDG1A4",
    authDomain: "legal-saas-9e860.firebaseapp.com",
    projectId: "legal-saas-9e860",
    storageBucket: "legal-saas-9e860.firebasestorage.app",
    messagingSenderId: "815927983363",
    appId: "1:815927983363:web:a6856fc426ed85cd66fd36",
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
});