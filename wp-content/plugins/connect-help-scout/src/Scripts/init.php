<?php
namespace WP2\Connect\HelpScout\Scripts;

class Controller
{
    private $beacon_id;

    public function __construct()
    {
        // Set the Beacon ID from the defined constant
        $this->beacon_id = defined('WP2_HELP_SCOUT_BEACON_ID') ? WP2_HELP_SCOUT_BEACON_ID : '';

        // Hook to enqueue the script
        add_action('wp_enqueue_scripts', [$this, 'enqueue_helpscout_script']);
    }

    public function enqueue_helpscout_script()
    {
        if (!empty($this->beacon_id)) {

            wp_localize_script('', 'wp2s', [
                'help_scout' => [
                    'beacons' => [
                        $this->beacon_id => [
                            'color' => '#02ACF2',
                            'mode' => 'selfService',
                            'display' => [
                                'style' => 'iconAndText',
                                'text' => 'Chat',
                                'textAlignment' => 'center',
                                'iconImage' => 'message',
                                'position' => 'right',
                                'zIndex' => 999999,
                                'horizontalOffset' => 40,
                                'verticalOffset' => 40,
                                'horizontalMobileOffset' => 20,
                                'verticalMobileOffset' => 20
                            ],
                            'messaging' => [
                                'chatEnabled' => true,
                                'contactForm' => [
                                    'showName' => true,
                                ]
                            ],
                            'labels' => [
                                // Answers
                                'suggestedForYou' => 'Instant Answers',
                                'getInTouch' => 'Get in touch',
                                'searchLabel' => 'What can we help you with?',
                                'tryAgain' => 'Try again',
                                'defaultMessageErrorText' => 'There was a problem sending your message. Please try again in a moment.',
                                'beaconButtonClose' => 'Close',
                                'beaconButtonChatMinimize' => 'Minimise chat',
                                'beaconButtonChatOpen' => 'Open chat',

                                // Ask
                                'answer' => 'Answers',
                                'ask' => 'Ask',
                                'messageButtonLabel' => 'Email',
                                'noTimeToWaitAround' => 'No time to wait around? We usually respond within a few hours',
                                'chatButtonLabel' => 'Chat',
                                'chatButtonDescription' => 'We’re online right now, talk with our team in real-time',
                                'wereHereToHelp' => 'Start a conversation',
                                'whatMethodWorks' => 'What channel do you prefer?',
                                'previousMessages' => 'Previous Conversations',

                                // Search Results
                                'cantFindAnswer' => 'Can’t find an answer?',
                                'relatedArticles' => 'Related Articles',
                                'nothingFound' => 'Hmm…',
                                'docsSearchEmptyText' => 'We couldn’t find any articles that match your search.',
                                'tryBroaderTerm' => 'Try searching a broader term, or',
                                'docsArticleErrorText' => 'There was a problem retrieving this article. Please double-check your internet connection and try again.',
                                'docsSearchErrorText' => 'There was a problem retrieving articles. Please double-check your internet connection and try again.',
                                'escalationQuestionFeedback' => 'Did this answer your question?',
                                'escalationQuestionFeedbackNo' => 'No',
                                'escalationQuestionFeedbackYes' => 'Yes',
                                'escalationSearchText' => 'Browse our help docs for an answer to your question',
                                'escalationSearchTitle' => 'Keep searching',
                                'escalationTalkText' => 'Talk with a friendly member of our support team',
                                'escalationTalkTitle' => 'Talk to us',
                                'escalationThanksFeedback' => 'Thanks for the feedback',
                                'escalationWhatNext' => 'What next?',

                                // Send A Message
                                'sendAMessage' => 'Send a message',
                                'firstAFewQuestions' => 'Let’s begin with a few questions',
                                'howCanWeHelp' => 'How can we help?',
                                'responseTime' => 'We usually respond in a few hours',
                                'history' => 'History',
                                'uploadAnImage' => 'Upload an image',
                                'attachAFile' => 'Attach a file',
                                'continueEditing' => 'Continue writing…',
                                'lastUpdated' => 'Last updated',
                                'you' => 'You',
                                'nameLabel' => 'Name',
                                'subjectLabel' => 'Subject',
                                'emailLabel' => 'Email address',
                                'messageLabel' => 'How can we help?',
                                'messageSubmitLabel' => 'Send a message',
                                'next' => 'Next',
                                'weAreOnIt' => 'We’re on it!',
                                'messageConfirmationText' => 'You’ll receive an email reply within a few hours.',
                                'viewAndUpdateMessage' => 'You can view and update your message in',
                                'mayNotBeEmpty' => 'May not be empty',
                                'customFieldsValidationLabel' => 'Please complete all fields',
                                'emailValidationLabel' => 'Please use a valid email address',
                                'attachmentErrorText' => 'There was a problem uploading your attachment. Please try again in a moment.',
                                'attachmentSizeErrorText' => 'Attachments may be no larger than 10MB',

                                // Previous Messages
                                'addReply' => 'Add a reply',
                                'addYourMessageHere' => 'Add your message here...',
                                'sendMessage' => 'Send message',
                                'received' => 'Received',
                                'waitingForAnAnswer' => 'Waiting for an answer',
                                'previousMessageErrorText' => 'There was a problem retrieving this message. Please double-check your Internet connection and try again.',
                                'justNow' => 'Just Now',

                                // Chat
                                'chatHeadingTitle' => 'Chat with our team',
                                'chatHeadingSublabel' => 'We’ll be with you soon',
                                'chatEndCalloutHeading' => 'All done!',
                                'chatEndCalloutMessage' => 'A copy of this conversation will land in your inbox shortly.',
                                'chatEndCalloutLink' => 'Return home',
                                'chatEndUnassignedCalloutHeading' => 'Sorry about that',
                                'chatEndUnassignedCalloutMessage' => 'It looks like nobody made it to your chat. We’ll send you an email response as soon as possible.',
                                'chatEndWaitingCustomerHeading' => 'Sorry about that',
                                'chatEndWaitingCustomerMessage' => 'Your question has been added to our email queue and we’ll get back to you shortly.',
                                'ending' => 'Ending...',
                                'endChat' => 'End chat',
                                'chatEnded' => ' ended the chat',
                                'chatConnected' => 'Connected to ',
                                'chatbotName' => 'Help Bot',
                                'chatbotGreet' => 'Hi there! You can begin by asking your question below. Someone will be with you shortly.',
                                'chatbotPromptEmail' => 'Got it. Real quick, what’s your email address? We’ll use it for any follow-up messages.',
                                'chatbotConfirmationMessage' => 'Thanks! Someone from our team will jump into the chat soon.',
                                'chatbotGenericErrorMessage' => 'Something went wrong sending your message, please try again in a few minutes.',
                                'chatbotInactivityPrompt' => 'Since the chat has gone idle, I’ll end this chat in a few minutes.',
                                'chatbotInvalidEmailMessage' => 'Looks like you’ve entered an invalid email address. Want to try again?',
                                'chatbotAgentDisconnectedMessage' => ' has disconnected from the chat. It’s possible they lost their internet connection, so I’m looking for someone else to take over. Sorry for the delay!',
                                'chatAvailabilityChangeMessage' => 'Our team’s availability has changed and there’s no longer anyone available to chat. Send us a message instead and we’ll get back to you.',

                                // Transcript Email
                                'emailHeading' => 'Today’s chat with ',
                                'emailGreeting' => 'Hey ',
                                'emailCopyOfDiscussion' => 'Here’s a copy of your discussion',
                                'emailContinueConversation' => 'If you’ve got any other questions, feel free to hit reply and continue the conversation.',
                                'emailJoinedLineItem' => ' joined the chat',
                                'emailEndedLineItem' => ' ended the chat',
                                'emailYou' => 'You'
                            ]
                        ]
                    ]
                ]
            ]);
        }
    }
}

// Instantiate the Controller to ensure it runs
new Controller();