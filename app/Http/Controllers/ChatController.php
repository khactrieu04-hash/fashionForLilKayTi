<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    protected $apiKey;
    protected $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GOOGLE_GEMINI_API_KEY');
    }

    public function sendMessage(Request $request)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string|max:1000',
            ]);

            $userMessage = $validated['message'];

            // Tạo prompt hệ thống cho AI tư vấn
            $systemPrompt = "Bạn là một trợ lý tư vấn cho cửa hàng thời trang MinMupShop. Nhiệm vụ của bạn là:\n" .
                "1. Tư vấn về sản phẩm thời trang\n" .
                "2. Giúp khách hàng lựa chọn phù hợp\n" .
                "3. Trả lời câu hỏi về chính sách, giao hàng, trả hàng\n" .
                "4. Luôn lịch sự, thân thiện, và hữu ích\n\n" .
                "Hãy trả lời ngắn gọn, rõ ràng (dưới 200 từ) và bằng tiếng Việt.";

            $requestBody = [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $systemPrompt . "\n\nKhách hàng: " . $userMessage
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1024,
                ]
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '?key=' . $this->apiKey, $requestBody);

            // Log response cho debugging
            \Log::info('Gemini API Response:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->failed()) {
                $errorData = $response->json();
                \Log::error('Gemini API Error:', $errorData);

                $errorMessage = 'Có lỗi từ AI service.';
                if (isset($errorData['error'])) {
                    $errorMessage = $errorData['error']['message'] ?? $errorMessage;
                }

                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => $errorData
                ], 500);
            }

            $data = $response->json();

            // Extract text from response
            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                \Log::error('Unexpected Gemini response format:', $data);
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể lấy phản hồi từ AI.',
                    'error' => $data
                ], 500);
            }

            $aiMessage = $data['candidates'][0]['content']['parts'][0]['text'];

            return response()->json([
                'success' => true,
                'message' => $aiMessage,
                'timestamp' => now()->format('H:i')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . json_encode($e->errors())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Chat Exception:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
