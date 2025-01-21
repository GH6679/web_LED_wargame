# War Gmae project

## ▶️ 개요

추가 예정

## ▶️ 개발 동기

추가 예정

## ▶️ 개발 목표

추가 예정

## ▶️ 주요 기능 설명

  ### 유저
  - 로그인, 회원가입 ,로그아웃 기능
  - 로그인한 유저를 추적하고 중복 로그인을 방지
  - 유저가 클리어한 챌린지의 통계를 보여주는 페이지 제공
  
  ### 챌린지
  - 손쉬운 챌린지 등록을 위한 페이지 제공 (WEB/SSH/게시글 형태 등 지원)
  - 챌린지 카드 라벨을 클릭해서 해당 챌린지 페이지로 이동되고 클리어 키값을 받아 입력시 클리어 처리(키값은 30자)
  - 유저간의 통계 및 순위를 표시해주는 스코어 보드 페이지 제공
  
  ### 메인페이지
  - 전체 문제, 로그인 유저, 전체 챌린지 횟수 통계 표시
  - 최근 클리어한 챌린지 표시 (최대 3개)
  - 모든 유저 챌린지 현황 표시 (최대 8개 10초 간격으로 갱신)

## ▶️ 기능 구현 현황
  

## ▶️ 개발 일정

## ▶️ 개발 환경
  ### 플렛폼
  - Rocky Linux v9.0
  
  ### 기타
  - MariaDB v10.5.22
  - PHP v8.0.30
  - node.js v22.11.0

## ▶️ 사용 IDE
**VScode(Cursor) v0.44.8**

## ▶️ 기술 스택
![HTML5](https://img.shields.io/badge/HTML5-FF6347?style=flat&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)
![Node.js](https://img.shields.io/badge/Node.js-339933?style=flat&logo=node.js&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00618D?style=flat&logo=mysql&logoColor=white)

## ▶️ END POINT

| **END POINT**                        | **METHOD** | **DESCRIPTION**             |
|--------------------------------------|------------|-----------------------------|
| `/php/login.php`                     | POST       | 로그인 처리                |
| `/php/signin.php`                    | POST       | 회원가입 처리              |
| `/php/logout.php`                    | GET        | 로그아웃 처리              |
| `/php/getUChallengesData.php`        | GET        | 사용자 챌린지 통계 받아오기 |
| `/php/getMainData.php`               | GET        | 메인 페이지 유저 통계 불러오기 |
| `/php/getChallenges.php`             | POST       | 챌린지 목록 불러오기        |
| `/php/setkey.php`                    | POST       | 키값 설정                  |
| `/php/getScoreBoard.php`             | GET        | 스코어 보드 데이터 가져오기 |
| `/php/getUserDataList.php`           | GET        | 사용자 데이터 리스트 가져오기 |
| `/php/getPointData.php`              | GET        | 포인트 데이터 가져오기     |
| `/php/getDifficultyData.php`         | GET        | 난이도 데이터 가져오기     |
| `/php/logout.php`                    | GET        | 로그아웃 처리              |
| `ws://192.168.1.133:30000`           | WebSocket  | WebSocket 연결             |




















