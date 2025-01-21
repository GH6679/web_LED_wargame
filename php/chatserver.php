<?php
require __DIR__ . './../vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatServer implements MessageComponentInterface
{
    protected $clients;
    protected $clientColors;  // 클라이언트 색상 저장 용 변수


    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->clientColors = []; // 클라이언트 색상 저장 용 변수 초기화
    }

    // 랜덤 색상 생성 함수
    private function generateRandomColor()
    {
        // 가독성을 위해 너무 밝거나 어두운 색은 제외
        $colors = [
            '#e74c3c',
            '#e67e22',
            '#f1c40f',
            '#2ecc71',
            '#1abc9c',
            '#3498db',
            '#9b59b6',
            '#34495e',
            '#16a085',
            '#27ae60',
            '#2980b9',
            '#8e44ad',
            '#2c3e50',
            '#f39c12',
            '#d35400',
            '#c0392b',
            '#7f8c8d'
        ];
        return $colors[array_rand($colors)];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "새 클라이언트가 연결되었습니다: {$conn->resourceId}\n";
        // 추가적인 디버깅용 로그
        echo "클라이언트 IP: " . $conn->remoteAddress . "\n";
        // 추가 디버깅 정보
        echo "현재 연결된 클라이언트 수: " . count($this->clients) . "\n";
        echo "연결 정보: " . print_r($conn->httpRequest->getHeaders(), true) . "\n";


        // 새로운 색상 할당
        $color = $this->generateRandomColor();
        $this->clientColors[$conn->resourceId] = $color;

        echo "새 클라이언트 연결: {$conn->resourceId} (색상: {$color})\n";

        // 클라이언트에게 할당된 색상 전송
        $conn->send(json_encode([
            'type' => 'color',
            'color' => $color
        ]));
    }

    // 메시지 수신 로직
    public function onMessage(ConnectionInterface $from, $msg)
    {

        $messageData = json_decode($msg, true);

        // 유저 닉네임
        $nickname = $messageData['nickname'];
        // 유저 포인트
        $point = $messageData['point'];
        // 유저 색상
        $color = $this->clientColors[$from->resourceId] ?? '#000000';

        if ($messageData['type'] == 'user_connect') {

            // 유저 입장 알림
            foreach ($this->clients as $client) {
                try {
                    $messageData = json_encode([
                        'type' => 'systemmsg',
                        'sender' => $from->resourceId,
                        'nickname' => $nickname,
                        'message' => "님이 입장하였습니다.",
                        'point' => $point,
                        'color' => $color,
                        'timestamp' => date('Y-m-d H:i:s')
                    ]);
                    $client->send($messageData);
                } catch (\Exception $e) {
                    echo "메시지 전송 실패: " . $e->getMessage() . "\n";
                }
            }
        } else {

            // 유저 메시지
            $message = $messageData['message'];

            echo "받은 메시지: {$msg}\n";
            echo "보낸 클라이언트 ID: {$from->resourceId}\n";
            echo "현재 연결된 클라이언트 수: " . count($this->clients) . "\n";


            // 클라이언트에 메시지를 전송
            foreach ($this->clients as $client) {
                // if ($from !== $client) {
                try {
                    $messageData = json_encode([
                        'type' => 'message',
                        'sender' => $from->resourceId,
                        'nickname' => $nickname,
                        'message' => $message,
                        'point' => $point,
                        'color' => $color,
                        'timestamp' => date('Y-m-d H:i:s')
                    ]);

                    $client->send($messageData);
                } catch (\Exception $e) {
                    // 메시지 전송 실패 로그
                    echo "메시지 전송 실패: " . $e->getMessage() . "\n";
                }
                // }
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);

        // 색상 정보 제거
        unset($this->clientColors[$conn->resourceId]);
        // 종료 로그
        echo "클라이언트가 연결을 종료했습니다: {$conn->resourceId}\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "에러 발생: {$e->getMessage()}\n";
        $conn->close();
    }
}

// 서버 실행
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    30000
);

echo "WebSocket 서버가 실행 중입니다 (포트: 30000)...\n";
$server->run();
