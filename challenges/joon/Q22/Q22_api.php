<?php
header('Content-Type: application/json');

// 데이터베이스 모의
$users = [
    1 => [
        'id' => 1,
        'username' => 'admin',
        'email' => 'admin@cybercity.kr',
        'role' => 'admin',
        'privateNote' => 'rXiYENXYvcpbmAwS7xpT4571jT3ezZ',
        'friends' => [2, 3]
    ],
    2 => [
        'id' => 2,
        'username' => 'user1',
        'email' => 'user1@cybercity.kr',
        'role' => 'user',
        'privateNote' => 'My secret note',
        'friends' => [1]
    ],
    3 => [
        'id' => 3,
        'username' => 'user2',
        'email' => 'user2@cybercity.kr',
        'role' => 'user',
        'privateNote' => 'Another secret note',
        'friends' => [1]
    ]
];

// GraphQL 쿼리 파싱 함수
function parseQuery($query) {
    $fields = [];
    if (preg_match('/{\s*me\s*{([^}]+)}/i', $query, $matches)) {
        preg_match_all('/\b(\w+)\b/', $matches[1], $fieldMatches);
        $fields = $fieldMatches[0];
    }
    return $fields;
}

// 요청된 필드만 반환하는 함수
function filterFields($data, $requestedFields) {
    $filtered = [];
    foreach ($requestedFields as $field) {
        if (isset($data[$field])) {
            $filtered[$field] = $data[$field];
        }
    }
    return $filtered;
}

// 친구 데이터 가져오기
function getFriendData($userId, $users, $requestedFields) {
    $friendIds = $users[$userId]['friends'];
    $friendData = [];
    foreach ($friendIds as $friendId) {
        if (isset($users[$friendId])) {
            $friendData[] = filterFields($users[$friendId], $requestedFields);
        }
    }
    return $friendData;
}

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
$query = $input['query'];

if (strpos($query, '__schema') !== false) {
    // 스키마 정보 반환 (힌트 포함)
    echo json_encode([
        'data' => [
            '__schema' => [
                'types' => [
                    [
                        'name' => 'User',
                        'fields' => [
                            ['name' => 'id'],
                            ['name' => 'username'],
                            ['name' => 'email'],
                            ['name' => 'role'],
                            ['name' => 'friends'],
                            ['name' => 'privateNote', 'description' => 'LedTeam의 비밀 노트']
                        ]
                    ]
                ]
            ]
        ]
    ]);
} else {
    $requestedFields = parseQuery($query);
    
    if (in_array('friends', $requestedFields)) {
        // friends 필드가 요청된 경우
        $friendFields = ['id', 'username', 'email', 'role']; // 기본 필드만 허용
        if (strpos($query, 'privateNote') !== false) {
            // privateNote가 요청된 경우 추가
            $friendFields[] = 'privateNote';
        }
        $friendData = getFriendData(2, $users, $friendFields);
        echo json_encode([
            'data' => [
                'me' => [
                    'friends' => $friendData
                ]
            ]
        ]);
    } else {
        // 기본 me 쿼리
        echo json_encode([
            'data' => [
                'me' => filterFields($users[2], $requestedFields)
            ]
        ]);
    }
}
?> 