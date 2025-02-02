# Autenticando Requisições

Para autenticar requisições, inclua um cabeçalho **Authorization** com o valor **"Bearer {ACCESS_TOKEN}"**.

Obtenha o token de acesso através do endpoint **POST /oauth/token**

Todos os endpoints que precisam de autenticação estão marcados com um selo requires authentication na documentação abaixo.

## 💳 Gerar o Token de Acesso

Para gerar o **Token de Acesso**, você precisará dos seguintes parâmetros:  
- **client_id** 🆔 9e1dca0f-8684-4b96-89e8-c8a5ad1d4eb3  
- **client_secret** 🔑 41251dc3-5fed-44a3-bb73-a9d13b9e13fc 

### 📌 Exemplo de Requisição  

```json
{
    "grant_type": "client_credentials",
    "client_id": "9e1dca0f-8684-4b96-89e8-c8a5ad1d4eb3",
    "client_secret": "41251dc3-5fed-44a3-bb73-a9d13b9e13fc"
}

```
```json  
{
    "token_type": "Bearer",
    "expires_in": 600,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5ZTFkY2EwZi04Njg0LTRiOTYtODllOC1jOGE1YWQxZDRlYjMiLCJqdGkiOiJmOTdlMGJhZjU1NzQ5YWZlYWU4OGFmOTQ3MTU2NzE4NGExNGYzMjgyYTZjYzZhNzI3MmIzYzE3OTUyYTI4OGU2ODE5MjM4ZTA4MDQ5OWM3NyIsImlhdCI6MTczODUyODYyMC41MDc2OTUsIm5iZiI6MTczODUyODYyMC41MDc2OTksImV4cCI6MTczODUyOTIyMC40OTY1NDgsInN1YiI6IiIsInNjb3BlcyI6W119.bcdGxNSOF4-yPmnUxLX8eSXD6dPCvV3GrcPlBHbEs-fo2qmAsGmvs3L7B_tms9EpsMVu35vpeuSJwSGiBMLIB53FA9P27IIEKYbo3atFB6oEdI5wzD6pdJcupe5k0E_oFBDjKDTfZD1uhu-Xb5ka692VooevswK8tVeBwLJeT2-iVleI9s_4gF8CGCXlp9_RkwgpXBYMo8gBLMT9EIVGE48HwfTl8R1oofko2dYMxPuZ58fnx8QwqO_vKTMSc_vh8t02_cMbSz6vFtxwFA_UOHlfhWJUbEmChKq2I8Mrx47EKbEdxj7PWKvGPJzeq6mwCzbjUZr4Usf1qAMovxP-kCAUNMYfZLAwfzmo0MAS49l7WODorWX-Jc01tTXP4_DM6OOeLyuIw-lWorcgyk8XS9TGlwZSCSsw29dph9XECm2LElPD9oWnwAv_GQoKF775cPxn5PyARWQFnaFJXGZl7VpFe2gnJXN7Dg4FOgKh_lirok_6XsDHJSmmCqY9_FfKhTrEdlN5YHvsO7_giH08Xra-1-8DrUU5VRpwNu2uO1BGY6a8jbyRjzE7RuIHypKHOeBAlhxmzYlV8xcl_Gx8fxwldBUpmqNUJtwfgPkc50frCHDdiw2dpHxjvtSG3_c-SzAonvF59notx1npj8SFN_JRkBppZUKLSXUEBqz5tos"
}
```