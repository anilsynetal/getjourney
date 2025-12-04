<!-- Boat Widget - AI Visa Enquiry Agent -->
<div id="boatWidget" class="boat-widget">
    <!-- Boat Widget Button -->
    <div class="boat-toggle-btn" id="boatToggle" title="Chat with Visa Agent">
        <i class="fa fa-ship"></i>
        <span class="boat-badge" id="boatBadge" style="display: none;">1</span>
    </div>

    <!-- Boat Widget Container -->
    <div class="boat-container" id="boatContainer" style="display: none;">
        <!-- Header -->
        <div class="boat-header">
            <div class="boat-header-content">
                <h3>
                    <i class="fa fa-ship"></i> Visa Assistant
                </h3>
                <p>AI-Powered Visa Consultant</p>
            </div>
            <button class="boat-close-btn" id="boatClose">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <!-- Messages Display Area -->
        <div class="boat-messages" id="boatMessages">
            <div class="boat-message bot-message">
                <div class="message-content">
                    <p>Hey there! 👋 I'm your AI Visa Assistant from GetJourney Tours. I'll help you explore visa
                        options and gather your information in just 5 minutes! Ready to get started? Let's begin -
                        what's your full name?</p>
                </div>
                <span class="message-time">Just now</span>
            </div>
        </div>

        <!-- Input Area -->
        <div class="boat-input-area">
            <form id="boatMessageForm" class="boat-form">
                <div class="input-group">
                    <input type="text" id="boatMessageInput" class="boat-input" placeholder="Type your response..."
                        autocomplete="off" required>
                    <button type="submit" class="boat-send-btn" title="Send" id="boatSubmitBtn">
                        <i class="fa fa-paper-plane"></i>
                    </button>
                </div>
                <small class="form-text text-muted" style="display: block; margin-top: 5px;">
                    This conversation will be saved for our team
                </small>
            </form>
        </div>

        <!-- Footer -->
        <div class="boat-footer">
            <small>
                <i class="fa fa-shield"></i> Your information is secure & confidential
            </small>
        </div>
    </div>
</div>

<style>
    /* Boat Widget Styles */
    .boat-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Toggle Button */
    .boat-toggle-btn {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
        transition: all 0.3s ease;
        font-size: 28px;
        color: white;
        position: relative;
        border: none;
    }

    .boat-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.6);
    }

    .boat-toggle-btn:active {
        transform: scale(0.95);
    }

    /* Badge for unread */
    .boat-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-5px);
        }
    }

    /* Container */
    .boat-container {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 380px;
        height: 500px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 40px rgba(0, 0, 0, 0.16);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    /* Header */
    .boat-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .boat-header-content h3 {
        margin: 0 0 5px 0;
        font-size: 16px;
        font-weight: 600;
    }

    .boat-header-content p {
        margin: 0;
        font-size: 13px;
        opacity: 0.9;
    }

    .boat-close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        padding: 0;
        transition: transform 0.2s;
    }

    .boat-close-btn:hover {
        transform: rotate(90deg);
    }

    /* Messages */
    .boat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
        background: #f5f5f5;
    }

    .boat-message {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .bot-message .message-content {
        background: white;
        color: #333;
        padding: 12px 16px;
        border-radius: 18px 18px 18px 0;
        max-width: 80%;
    }

    .user-message {
        flex-direction: row-reverse;
    }

    .user-message .message-content {
        background: #007bff;
        color: white;
        padding: 12px 16px;
        border-radius: 18px 18px 0 18px;
        max-width: 80%;
    }

    .message-content {
        margin: 0;
        font-size: 14px;
        word-wrap: break-word;
    }

    .message-content p {
        margin: 0;
    }

    .message-time {
        font-size: 11px;
        color: #999;
        margin-top: 5px;
        display: block;
    }

    /* Input Area */
    .boat-input-area {
        padding: 15px;
        border-top: 1px solid #eee;
        background: white;
        transition: all 0.5s ease;
    }

    .boat-input-area.hidden {
        opacity: 0;
        visibility: hidden;
        max-height: 0;
        padding: 0;
        border-top: none;
    }

    .boat-form {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .input-group {
        display: flex;
        gap: 8px;
    }

    .boat-input {
        flex: 1;
        border: 1px solid #ddd;
        border-radius: 24px;
        padding: 10px 16px;
        font-size: 14px;
        outline: none;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .boat-input:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    .boat-input::placeholder {
        color: #999;
    }

    .boat-input:disabled {
        background-color: #f0f8f0;
        color: #666;
        border-color: #ddd;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .boat-send-btn {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        border-radius: 50%;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        font-size: 16px;
    }

    .boat-send-btn:hover:not(:disabled) {
        transform: scale(1.05);
    }

    .boat-send-btn:disabled {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        cursor: not-allowed;
        opacity: 0.6;
    }

    .boat-send-btn:hover {
        transform: scale(1.05);
    }

    .boat-send-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .boat-form .form-group {
        margin: 0;
    }

    /* Footer */
    .boat-footer {
        padding: 10px 15px;
        background: #f9f9f9;
        border-top: 1px solid #eee;
        font-size: 12px;
        color: #666;
        text-align: center;
    }

    /* Scrollbar styling */
    .boat-messages::-webkit-scrollbar {
        width: 6px;
    }

    .boat-messages::-webkit-scrollbar-track {
        background: transparent;
    }

    .boat-messages::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 3px;
    }

    .boat-messages::-webkit-scrollbar-thumb:hover {
        background: #ccc;
    }

    /* Responsive */
    @media (max-width: 480px) {
        .boat-container {
            width: calc(100vw - 30px);
            height: 70vh;
            bottom: 70px;
        }

        .bot-message .message-content,
        .user-message .message-content {
            max-width: 85%;
        }
    }

    /* Dark Mode */
    @media (prefers-color-scheme: dark) {
        .boat-container {
            background: #2d2d2d;
        }

        .boat-messages {
            background: #1e1e1e;
        }

        .bot-message .message-content {
            background: #3d3d3d;
            color: #e0e0e0;
        }

        .boat-input {
            background: #3d3d3d;
            color: #e0e0e0;
            border-color: #555;
        }

        .boat-input::placeholder {
            color: #999;
        }

        .boat-footer {
            background: #2d2d2d;
            color: #999;
            border-color: #444;
        }

        .boat-input-area {
            background: #2d2d2d;
            border-color: #444;
        }
    }
</style>

<script>
    $(function() {
        // AI Agent State Management
        const aiAgent = {
            // Conversation state
            currentStep: 0,
            conversationData: {
                fullName: '',
                email: '',
                phone: '',
                age: '',
                nationality: '',
                qualification: '',
                workExperience: '',
                destinationCountry: '',
                visaType: '',
                travelDate: '',
                duration: '',
                purpose: '',
                currentOccupation: '',
                company: '',
                previousVisas: '',
                familyStatus: '',
                assets: '',
                additionalInfo: ''
            },

            // Questions sequence
            questions: [{
                    key: 'fullName',
                    question: "Nice to meet you! What's your full name? 😊"
                },
                {
                    key: 'email',
                    question: "Great! And what's the best email to reach you at?"
                },
                {
                    key: 'phone',
                    question: "Perfect! What's your phone number?"
                },
                {
                    key: 'age',
                    question: "How old are you?"
                },
                {
                    key: 'nationality',
                    question: "Which country are you from?"
                },
                {
                    key: 'qualification',
                    question: "What's your highest level of education? (Bachelor's, Master's, High School, etc.)"
                },
                {
                    key: 'workExperience',
                    question: "How many years of work experience do you have?"
                },
                {
                    key: 'currentOccupation',
                    question: "What's your current job title or occupation?"
                },
                {
                    key: 'company',
                    question: "Which company are you working for?"
                },
                {
                    key: 'destinationCountry',
                    question: "Which country are you interested in? 🌍"
                },
                {
                    key: 'visaType',
                    question: "What type of visa interests you? (Work, Study, Business, Visit, etc.)"
                },
                {
                    key: 'purpose',
                    question: "What's your main goal? (Work, Education, Business, Tourism, Family, etc.)"
                },
                {
                    key: 'travelDate',
                    question: "When are you planning to go? (e.g., January 2026, Next year)"
                },
                {
                    key: 'duration',
                    question: "How long do you plan to stay? (e.g., 2 years, 6 months)"
                },
                {
                    key: 'previousVisas',
                    question: "Have you traveled on visas before? If yes, which countries?"
                },
                {
                    key: 'familyStatus',
                    question: "Are you traveling alone or with family? (Single, Married, With dependents?)"
                },
                {
                    key: 'assets',
                    question: "Do you have financial resources for this visa? (savings, sponsorship, etc.)"
                },
                {
                    key: 'additionalInfo',
                    question: "Any special circumstances or additional info about your situation?"
                }
            ],

            // Get current question
            getCurrentQuestion: function() {
                return this.questions[this.currentStep] || null;
            },

            // Move to next question
            nextQuestion: function() {
                this.currentStep++;
                return this.getCurrentQuestion();
            },

            // Save answer
            saveAnswer: function(key, value) {
                this.conversationData[key] = value;
            },

            // Check if done
            isDone: function() {
                return this.currentStep >= this.questions.length;
            },

            // Get all collected data
            getAllData: function() {
                return this.conversationData;
            }
        };

        // DOM Elements
        const boatToggle = $('#boatToggle');
        const boatContainer = $('#boatContainer');
        const boatClose = $('#boatClose');
        const boatMessageForm = $('#boatMessageForm');
        const boatMessageInput = $('#boatMessageInput');
        const boatMessages = $('#boatMessages');
        const boatSubmitBtn = $('#boatSubmitBtn');

        let isBoatOpen = false;

        // Toggle boat widget
        boatToggle.on('click', function() {
            isBoatOpen = !isBoatOpen;
            if (isBoatOpen) {
                boatContainer.slideDown(300);
                setTimeout(() => {
                    boatMessageInput[0].focus();
                }, 350);
                $('#boatBadge').hide();
            } else {
                boatContainer.slideUp(300);
            }
        });

        // Close boat widget
        boatClose.on('click', function(e) {
            e.preventDefault();
            isBoatOpen = false;
            boatContainer.slideUp(300);
        });

        // Validation functions
        function validateInput(key, value) {
            value = value.trim();

            // Check if empty
            if (!value) {
                addMessageToChat("❌ Please provide an answer before proceeding.", 'bot');
                return false;
            }

            // Specific validations based on field type
            switch (key) {
                case 'fullName':
                    if (value.length < 3) {
                        addMessageToChat("❌ Please enter a valid full name (at least 3 characters).", 'bot');
                        return false;
                    }
                    if (!/^[a-zA-Z\s'-]+$/.test(value)) {
                        addMessageToChat("❌ Name should contain only letters, spaces, hyphens, or apostrophes.",
                            'bot');
                        return false;
                    }
                    // Check for repeated characters (like "aaaa" or "bbbb")
                    if (/(.)\1{3,}/.test(value)) {
                        addMessageToChat("❌ Please enter a valid name without repeated characters.", 'bot');
                        return false;
                    }
                    break;

                case 'email':
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                        addMessageToChat("❌ Please enter a valid email address (e.g., name@example.com).",
                            'bot');
                        return false;
                    }
                    break;

                case 'phone':
                    // Remove common formatting characters
                    const phoneDigits = value.replace(/[\s\-\+\(\)]/g, '');
                    if (!/^\d{7,15}$/.test(phoneDigits)) {
                        addMessageToChat("❌ Please enter a valid phone number (7-15 digits).", 'bot');
                        return false;
                    }
                    break;

                case 'age':
                    const age = parseInt(value);
                    if (isNaN(age) || age < 16 || age > 100) {
                        addMessageToChat("❌ Please enter a valid age (16-100).", 'bot');
                        return false;
                    }
                    break;

                case 'nationality':
                    if (value.length < 2) {
                        addMessageToChat("❌ Please enter a valid country name.", 'bot');
                        return false;
                    }
                    if (!/^[a-zA-Z\s'-]+$/.test(value)) {
                        addMessageToChat("❌ Country name should contain only letters.", 'bot');
                        return false;
                    }
                    break;

                case 'qualification':
                    if (value.length < 3) {
                        addMessageToChat("❌ Please provide a valid education qualification.", 'bot');
                        return false;
                    }
                    // Reject obvious gibberish like "sdfsd", "qwerty"
                    if (!/[aeiouAEIOU]/.test(value)) {
                        addMessageToChat("❌ Please enter a valid education level.", 'bot');
                        return false;
                    }
                    break;

                case 'workExperience':
                    const years = parseInt(value);
                    if (isNaN(years) || years < 0 || years > 70) {
                        addMessageToChat("❌ Please enter a valid number of years (0-70).", 'bot');
                        return false;
                    }
                    break;

                case 'currentOccupation':
                    if (value.length < 2) {
                        addMessageToChat("❌ Please enter a valid job title.", 'bot');
                        return false;
                    }
                    if (!/[aeiouAEIOU]/.test(value)) {
                        addMessageToChat("❌ Please enter a valid occupation.", 'bot');
                        return false;
                    }
                    break;

                case 'company':
                    if (value.length < 2) {
                        addMessageToChat("❌ Please enter a valid company name.", 'bot');
                        return false;
                    }
                    if (!/[aeiouAEIOU]/.test(value)) {
                        addMessageToChat("❌ Please enter a valid company name.", 'bot');
                        return false;
                    }
                    break;

                case 'destinationCountry':
                    if (value.length < 2) {
                        addMessageToChat("❌ Please enter a valid country name.", 'bot');
                        return false;
                    }
                    if (!/[aeiouAEIOU]/.test(value)) {
                        addMessageToChat("❌ Please enter a valid country name.", 'bot');
                        return false;
                    }
                    break;

                case 'visaType':
                    if (value.length < 2) {
                        addMessageToChat("❌ Please enter a valid visa type.", 'bot');
                        return false;
                    }
                    if (!/[aeiouAEIOU]/.test(value)) {
                        addMessageToChat("❌ Please enter a valid visa type.", 'bot');
                        return false;
                    }
                    break;

                case 'purpose':
                    if (value.length < 2) {
                        addMessageToChat("❌ Please provide a valid purpose.", 'bot');
                        return false;
                    }
                    if (!/[aeiouAEIOU]/.test(value)) {
                        addMessageToChat("❌ Please enter a valid purpose.", 'bot');
                        return false;
                    }
                    break;

                case 'travelDate':
                    if (value.toLowerCase() === 'asap' || value.toLowerCase() === 'immediately') {
                        // Accept common variants
                        break;
                    }
                    // Check if date is in future or reasonable format
                    if (value.length < 3) {
                        addMessageToChat(
                            "❌ Please provide a valid travel date (e.g., 'January 2026', 'Next month').",
                            'bot');
                        return false;
                    }
                    if (!/[aeiouAEIOU0-9]/.test(value)) {
                        addMessageToChat("❌ Please provide a valid travel date.", 'bot');
                        return false;
                    }
                    break;

                case 'duration':
                    if (value.length < 2) {
                        addMessageToChat("❌ Please specify a duration (e.g., '6 months', '2 years').", 'bot');
                        return false;
                    }
                    if (!/[aeiouAEIOU0-9]/.test(value)) {
                        addMessageToChat("❌ Please specify a valid duration.", 'bot');
                        return false;
                    }
                    break;

                case 'previousVisas':
                    if (value.length < 1) {
                        addMessageToChat("❌ Please provide an answer.", 'bot');
                        return false;
                    }
                    // Accept "No", "No visas", "Yes, ...", etc
                    break;

                case 'familyStatus':
                    if (value.length < 2) {
                        addMessageToChat("❌ Please provide a valid answer.", 'bot');
                        return false;
                    }
                    if (!/[aeiouAEIOU]/.test(value)) {
                        addMessageToChat("❌ Please provide a valid answer.", 'bot');
                        return false;
                    }
                    break;

                case 'assets':
                    if (value.length < 2) {
                        addMessageToChat("❌ Please provide a valid answer.", 'bot');
                        return false;
                    }
                    // Accept yes/no style answers
                    break;

                case 'additionalInfo':
                    // This is optional, but if provided, should not be gibberish
                    // Accept "no", "none", or text with vowels
                    const lowerValue = value.toLowerCase();
                    if (lowerValue !== 'no' && lowerValue !== 'none' && lowerValue !== 'n/a') {
                        // Must contain a vowel if not rejecting
                        if (!/[aeiouAEIOU]/.test(value)) {
                            addMessageToChat("❌ Please provide valid information (or just say 'No').", 'bot');
                            return false;
                        }
                    }
                    break;
            }

            return true;
        }

        // Handle message form submission
        boatMessageForm.on('submit', function(e) {
            e.preventDefault();

            const userMessage = boatMessageInput.val().trim();
            if (!userMessage) {
                addMessageToChat("❌ Please type a response.", 'bot');
                boatMessageInput[0].focus();
                return;
            }

            // Get current question
            const currentQ = aiAgent.getCurrentQuestion();
            if (!currentQ) return;

            // Validate the input
            if (!validateInput(currentQ.key, userMessage)) {
                boatMessageInput.val('');
                boatMessageInput[0].focus();
                return;
            }

            // Add user message to chat
            addMessageToChat(userMessage, 'user');
            boatMessageInput.val('');

            // Save the answer
            aiAgent.saveAnswer(currentQ.key, userMessage);

            // Get next question or finish
            boatSubmitBtn.prop('disabled', true);
            boatMessageInput.prop('disabled', true);

            setTimeout(() => {
                boatSubmitBtn.prop('disabled', false);
                boatMessageInput.prop('disabled', false);

                console.log('✅ Before nextQuestion - Current Step:', aiAgent.currentStep,
                    'Total Questions:', aiAgent.questions.length);

                // Move to next question
                const nextQ = aiAgent.nextQuestion();
                console.log('✅ After nextQuestion - Current Step:', aiAgent.currentStep,
                    'isDone():', aiAgent.isDone());

                if (aiAgent.isDone()) {
                    // All questions answered - submit to database
                    console.log('🎉 All questions done! Submitting enquiry...');
                    submitCompleteEnquiry();
                } else {
                    // Ask next question
                    console.log('➡️ Next question:', nextQ);
                    if (nextQ) {
                        addMessageToChat(nextQ.question, 'bot');
                    }

                    // Ensure focus is on input field after next question
                    setTimeout(() => {
                        boatMessageInput[0].focus();
                    }, 100);
                }
            }, 800);
        });

        // Add message to chat
        function addMessageToChat(message, sender) {
            const messageTime = new Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
            const messageClass = sender === 'user' ? 'user-message' : 'bot-message';

            // Convert \n to <br> for proper line breaks and escape HTML
            const escapedMessage = escapeHtml(message).replace(/\n/g, '<br>');

            const messageHTML = `
            <div class="boat-message ${messageClass}">
                <div class="message-content">
                    <p>${escapedMessage}</p>
                </div>
                <span class="message-time">${messageTime}</span>
            </div>
        `;

            boatMessages.append(messageHTML);
            boatMessages.scrollTop(boatMessages[0].scrollHeight);
        }

        // Generate AI suggestions based on visa type and profile
        function generateVisaSuggestions(data) {
            let suggestions = "🎯 Based on your profile, here are my recommendations:\n\n";
            const visaType = data.visaType.toLowerCase();

            // Visa-specific suggestions
            if (visaType.includes('work') || visaType.includes('employment')) {
                suggestions += "💼 Work Visa Route:\n";
                suggestions += "• You have " + data.workExperience + " years of experience\n";
                suggestions += "• Your role (" + data.currentOccupation + ") is in high demand\n";
                suggestions += "• Start preparing your employment documents\n";
                suggestions += "• Have employer sponsorship letter ready\n\n";
            } else if (visaType.includes('student') || visaType.includes('study')) {
                suggestions += "🎓 Student Visa Route:\n";
                suggestions += "• Your " + data.qualification + " qualification is a strong foundation\n";
                suggestions += "• Research universities in " + data.destinationCountry + "\n";
                suggestions += "• Prepare your academic transcripts\n";
                suggestions += "• Get your English proficiency test scores ready\n\n";
            } else if (visaType.includes('business') || visaType.includes('entrepreneur')) {
                suggestions += "📊 Business Visa Route:\n";
                suggestions += "• With your " + data.workExperience +
                    " years experience, you're well-positioned\n";
                suggestions += "• Prepare your business plan and financial statements\n";
                suggestions += "• Document your company's registration and credentials\n";
                suggestions += "• Show proof of business experience\n\n";
            } else if (visaType.includes('visit') || visaType.includes('tourist')) {
                suggestions += "✈️ Visit/Tourist Visa Route:\n";
                suggestions += "• Great choice for exploring " + data.destinationCountry + "!\n";
                suggestions += "• Prepare travel itinerary for your " + data.duration + " stay\n";
                suggestions += "• Gather proof of accommodation bookings\n";
                suggestions += "• Have return flight tickets ready\n\n";
            }

            // General next steps
            suggestions += "📋 General Next Steps:\n";
            suggestions += "1. Gather all required documents\n";
            suggestions += "2. Schedule a detailed consultation\n";
            suggestions += "3. Complete visa application forms\n";
            suggestions += "4. Prepare for visa interview (if required)\n\n";

            // Timeline
            suggestions += "⏱️ Timeline Guidance:\n";
            suggestions += "• Processing typically takes 4-12 weeks\n";
            suggestions += "• Your target date: " + data.travelDate + "\n";
            suggestions += "• Apply at least 2-3 months before travel\n";

            return suggestions;
        }

        // Submit complete enquiry to API
        function submitCompleteEnquiry() {
            console.log('🎯 submitCompleteEnquiry function called!');
            const data = aiAgent.getAllData();
            console.log('📊 Data collected from AI Agent:', data);
            console.log('📍 Current Step:', aiAgent.currentStep);
            console.log('📍 Total Questions:', aiAgent.questions.length);

            // Compile comprehensive message from conversation
            const compiledMessage = `
Full Name: ${data.fullName}
Email: ${data.email}
Phone: ${data.phone}
Age: ${data.age}
Nationality: ${data.nationality}
Education: ${data.qualification}
Work Experience: ${data.workExperience} years
Current Occupation: ${data.currentOccupation}
Current Company: ${data.company}
Destination Country: ${data.destinationCountry}
Visa Type: ${data.visaType}
Purpose: ${data.purpose}
Travel Date: ${data.travelDate}
Duration: ${data.duration}
Previous Visas: ${data.previousVisas}
Family Status: ${data.familyStatus}
Financial Resources: ${data.assets}
Additional Info: ${data.additionalInfo}
        `.trim();

            // Send all individual fields to API
            const enquiryData = {
                full_name: data.fullName,
                email: data.email,
                phone: data.phone,
                age: data.age,
                nationality: data.nationality,
                qualification: data.qualification,
                work_experience: data.workExperience,
                current_occupation: data.currentOccupation,
                company: data.company,
                destination_country: data.destinationCountry,
                visa_type: data.visaType,
                purpose: data.purpose,
                travel_date: data.travelDate,
                duration: data.duration,
                previous_visas: data.previousVisas,
                family_status: data.familyStatus,
                assets: data.assets,
                additional_info: data.additionalInfo,
                message: compiledMessage
            };

            console.log('📤 Sending AJAX request to:', '/api/v1/boat-widget-enquiries');
            console.log('📋 Enquiry Data:', enquiryData);

            $.ajax({
                url: '/api/v1/boat-widget-enquiries',
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                },
                data: JSON.stringify(enquiryData),
                timeout: 15000,
                dataType: 'json',
                success: function(response) {
                    console.log('✅ SUCCESS - Enquiry submitted:', response);
                    console.log('Data:', data);

                    // STEP 1: Thank you message (Immediate - 0s)
                    const thankYouMsg =
                        "✅ Excellent! I've successfully collected all your visa information.\n\nThank you for choosing GetJourney Tours, " +
                        (data.fullName || 'Guest') +
                        "! 🎉\n\nYour profile has been saved and our visa experts are reviewing it right now.";
                    console.log('Sending thank you message:', thankYouMsg);
                    addMessageToChat(thankYouMsg, 'bot');

                    // STEP 2: Hide input area and show completion state (Immediate)
                    setTimeout(() => {
                        console.log('Hiding input area...');
                        // Hide the input area completely with smooth animation
                        $('.boat-input-area').addClass('hidden');
                        boatMessageInput.prop('disabled', true);
                        boatSubmitBtn.prop('disabled', true);
                        console.log('Input area hidden');
                    }, 100);

                    // STEP 3: Personalized AI suggestions (1.2s)
                    setTimeout(() => {
                        console.log('Generating suggestions...');
                        const suggestions = generateVisaSuggestions(data);
                        addMessageToChat(suggestions, 'bot');
                    }, 1200);

                    // STEP 4: Next steps message (3s)
                    setTimeout(() => {
                        const emailDisplay = data.email || 'your registered email';
                        const nextStepsMsg =
                            "📋 What happens next:\n\n1️⃣ Our visa experts review your details (within 2 hours)\n2️⃣ You'll receive a personalized visa roadmap\n3️⃣ We'll schedule a consultation call\n4️⃣ Get step-by-step application guidance\n\n⏰ Expected contact: Within 24 hours\n📧 We'll email: " +
                            emailDisplay;
                        addMessageToChat(nextStepsMsg, 'bot');
                    }, 3000);

                    // STEP 5: Support contact options (4.8s)
                    setTimeout(() => {
                        const supportMsg =
                            "🤝 Need immediate assistance?\n\n📞 Call us: +1-800-VISA-HELP (1-800-847-2435)\n💬 WhatsApp: Available 24/7\n📧 Email: support@getjourneytours.com\n🕐 Live Chat: Available Mon-Fri 9AM-6PM EST\n\nOur team is here to help! 😊";
                        addMessageToChat(supportMsg, 'bot');
                    }, 4800);

                    // STEP 6: Final encouragement (6.5s)
                    setTimeout(() => {
                        addMessageToChat(
                            "✨ You're all set! We're excited to help you achieve your visa goals.\n\nClose this chat anytime. We have everything we need! 🚀\n\nThank you for trusting GetJourney Tours! 🌍✈️",
                            'bot'
                        );
                    }, 6500);
                },
                error: function(xhr, status, error) {
                    console.error('❌ ERROR submitting enquiry');
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('HTTP Status Code:', xhr.status);
                    console.error('Response Text:', xhr.responseText);
                    console.error('Full XHR:', xhr);

                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        console.error('Error Response JSON:', errorResponse);
                    } catch (e) {
                        console.error('Could not parse error response');
                    }

                    // Show error with helpful info
                    let errorMsg = "❌ There was an issue saving your information.\n\n";

                    if (xhr.status === 422) {
                        errorMsg += "⚠️ Validation Error - Please check your information\n";
                        try {
                            const errors = JSON.parse(xhr.responseText).errors || {};
                            for (let field in errors) {
                                errorMsg += "• " + field + ": " + errors[field][0] + "\n";
                            }
                        } catch (e) {}
                    } else if (xhr.status === 0) {
                        errorMsg += "⚠️ Network Error - Please check your internet connection\n";
                    } else if (xhr.status === 404) {
                        errorMsg += "❌ API endpoint not found\n";
                    } else if (xhr.status === 500) {
                        errorMsg += "❌ Server Error - Please try again later\n";
                    } else {
                        errorMsg += "Error Code: " + xhr.status + " (" + status + ")\n";
                    }

                    errorMsg += "\n📞 Contact Support:\n";
                    errorMsg += "+1-800-VISA-HELP\n";
                    errorMsg += "support@getjourneytours.com";

                    addMessageToChat(errorMsg, 'bot');

                    // Keep input enabled for retry
                    boatSubmitBtn.prop('disabled', false);
                    boatMessageInput.focus();
                }
            });
        }

        // Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Show badge after delay
        setTimeout(() => {
            $('#boatBadge').fadeIn();
        }, 3000);
    });
</script>
