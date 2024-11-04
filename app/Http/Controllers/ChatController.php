<?php

namespace App\Http\Controllers;

use App\Models\Chat;
 use GuzzleHttp\Client;
use Google\Auth\OAuth2;
use Illuminate\Http\Request;
use App\Services\ChatGPTService;
use Exception;
use GeminiAPI\Client as gemeniclient;
use GeminiAPI\Resources\Parts\TextPart;
use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\IntentsClient;
use Google\Cloud\Dialogflow\V2\CreateIntentRequest;
use Google\Cloud\Dialogflow\V2\Intent;
use Google\Cloud\Dialogflow\V2\Intent\Message\Text;
use Google\Cloud\Dialogflow\V2\Intent\TrainingPhrase;
use Google\Cloud\Dialogflow\V2\Intent\TrainingPhrase\Part;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Google\GenerativeAI\V1\GenerativeAIClient;

class ChatController extends Controller
{
 
  
    protected $client;
 

  

    

    
    public function addask(Request $request){
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);
        Chat::create([
            'question'=>$request->question,
            'answer'=>$request->answer
        ]);
        return response()->json(['message'=>'تم اضافة السؤال والاجابة بنجاح'],200);

    }

    public function ask(Request $request){
        $validatedData = $request->validate([
            'message' => 'required|string',
        ]);
    
       $message = strtolower($validatedData['message']);
    
        // البحث عن سؤال مشابه في قاعدة البيانات
        $question = Chat::where('question', 'LIKE', "%$message%")->first();
    
        if ($question) {
            $response = $question->answer;
        } else {
            $response = "عذرًا، لا أملك إجابة على هذا السؤال.";
        }
    
        return response()->json(['response' => $response]);
    }

   

   


    public function detectIntent(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $sessionsClient = new SessionsClient();
        $session = $sessionsClient->sessionName(env('DIALOGFLOW_PROJECT_ID'), uniqid());

        $textInput = (new \Google\Cloud\Dialogflow\V2\TextInput())
            ->setText($request->input('message'))
            ->setLanguageCode('ar'); // يمكنك تغيير اللغة حسب الحاجة

        $queryInput = (new \Google\Cloud\Dialogflow\V2\QueryInput())
            ->setText($textInput);

        $response = $sessionsClient->detectIntent($session, $queryInput);

        return response()->json([
            'reply' => $response->getQueryResult()->getFulfillmentText()
        ]);
    }

    public function addIntent(Request $request)
    {
        // التحقق من البيانات القادمة من Postman
        $validated = $request->validate([
            'agent_id' => 'required|string',
            'intent_name' => 'required|string',
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        // استخراج المتغيرات من الطلب
        $agentId = $validated['agent_id'];
        $intentName = $validated['intent_name'];
        $question = $validated['question'];
        $answer = $validated['answer'];

        try {
            // إنشاء عميل Dialogflow
            $client = new IntentsClient();
            $agentPath = $client->projectAgentName($agentId);

            // إعداد الجملة التدريبية (Training Phrase)
            $trainingPart = new Part(['text' => $question]);
            $trainingPhrase = new TrainingPhrase(['parts' => [$trainingPart]]);

            // إعداد الرد (Response)
            $responseText = new Text(['text' => [$answer]]);

            // إعداد intent
            $intent = (new Intent())
                ->setDisplayName($intentName)
                ->setTrainingPhrases([$trainingPhrase])
                ->setMessages([new Intent\Message(['text' => $responseText])]);

            // إرسال الطلب إلى Dialogflow
            $request = (new CreateIntentRequest())
                ->setParent($agentPath)
                ->setIntent($intent);

            $response = $client->createIntent($agentPath, $intent);
            $client->close();

            return response()->json(['message' => 'Intent created successfully', 'intent' => $response], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function ask1()
    {
        $client = new gemeniclient(env('GEMINI_API_KEY'));

        $response = $client->geminiPro()->generateContent(
            new TextPart('من هو ميسي')
        );
        
        print_r($response->text()) ;
    }
 public function askQuestion(Request $request)
    {
        $client = new gemeniclient(env('GEMINI_API_KEY'));

        $question = $request->input('question'); // استلام السؤال من Postman

        try {
            // استدعاء واجهة Gemini API
            $response = $client->geminiPro()->generateContent(
                new TextPart($question)
            );

            return response()->json([
                'status' => 'success',
                'answer' => $response->text(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    
    
}
