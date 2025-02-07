<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

class BufferOverflowSimulator {
    private $bufferSize = 32;
    private $sfpSize = 8;
    private $retSize = 8;
    private $getFlagAddr = "\xd6\x11\x40\x00\x00\x00\x00\x00";
    private $flag = "b08bVQvaEUT6ZOmtIGFvWBhPfQPRBN";

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? '';
        $payload = $input['payload'] ?? '';

        switch ($action) {
            case 'execute':
                return $this->executePayload($payload);
            case 'checkMemory':
                    
            case 'getSystemInfo':
                return $this->getSystemInfo();
            default:
                return $this->error('Invalid action');
        }
    }

    private function executePayload($payload) {
        $result = [
            'success' => false,
            'memoryState' => [],
            'messages' => [],
            'flag' => null
        ];

        try {
            // 페이로드 길이 검증
            $payloadLength = strlen($payload);
            $result['messages'][] = [
                'type' => 'info',
                'message' => "Received payload of length: {$payloadLength} bytes"
            ];

            // 메모리 상태 분석
            $memoryState = $this->analyzeMemoryState($payload);
            $result['memoryState'] = $memoryState;

            // 버퍼 오버플로우 체크
            if ($payloadLength > $this->bufferSize) {
                $result['messages'][] = [
                    'type' => 'warning',
                    'message' => 'Buffer overflow detected!'
                ];

                // 리턴 주소 체크
                if ($payloadLength >= ($this->bufferSize + $this->sfpSize + $this->retSize)) {
                    $retAddr = substr($payload, $this->bufferSize + $this->sfpSize, $this->retSize);
                    
                    if ($retAddr === $this->getFlagAddr) {
                        $result['success'] = true;
                        $result['flag'] = $this->flag;
                        $result['messages'][] = [
                            'type' => 'success',
                            'message' => 'Successfully overwrote return address!'
                        ];
                    }
                }
            }

            return $this->success($result);

        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    private function analyzeMemoryState($payload) {
        $state = [
            'buffer' => [
                'content' => bin2hex(substr($payload, 0, $this->bufferSize)),
                'overflow' => strlen($payload) > $this->bufferSize
            ],
            'sfp' => [
                'content' => bin2hex(substr($payload, $this->bufferSize, $this->sfpSize)),
                'overflow' => strlen($payload) > ($this->bufferSize + $this->sfpSize)
            ],
            'returnAddress' => [
                'content' => bin2hex(substr($payload, $this->bufferSize + $this->sfpSize, $this->retSize)),
                'overflow' => strlen($payload) > ($this->bufferSize + $this->sfpSize + $this->retSize)
            ]
        ];

        return $state;
    }

    private function getSystemInfo() {
        return $this->success([
            'bufferSize' => $this->bufferSize,
            'sfpSize' => $this->sfpSize,
            'retSize' => $this->retSize,
            'getFlagAddr' => '0x4011d6',
            'baseAddr' => '0x400000'
        ]);
    }

    private function success($data) {
        return [
            'status' => 'success',
            'data' => $data
        ];
    }

    private function error($message) {
        return [
            'status' => 'error',
            'message' => $message
        ];
    }
}

// 시뮬레이터 실행
$simulator = new BufferOverflowSimulator();
echo json_encode($simulator->handleRequest());