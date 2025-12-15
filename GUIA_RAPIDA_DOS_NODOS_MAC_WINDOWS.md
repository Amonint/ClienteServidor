# Guía rápida (2 nodos) - PC1 macOS (Cliente) + PC2 Windows (Servidor)

Objetivo: probar el módulo **Horarios de Limpieza** en una arquitectura Cliente-Servidor distribuida.

- **PC1 (macOS)**: NODO 1 = CLIENTE (proxy/reverse proxy opcional)
- **PC2 (Windows)**: NODO 2 = SERVIDOR (PHP + PostgreSQL + App)

> Reemplaza `IP_PC1` y `IP_PC2` por las IP reales.

---

## 0) Red (ambas PCs)

- Asegúrate de que estén en la misma red (Wi-Fi/LAN).
- Obtén IPs:
  - **macOS (PC1)**: `Ajustes del Sistema -> Red` o `ifconfig`.
  - **Windows (PC2)**: `ipconfig`.

Checklist:
- PC1 puede hacer `ping IP_PC2`
- PC2 puede hacer `ping IP_PC1`

---

## 1) PC2 (Windows) - SERVIDOR (PHP + PostgreSQL + App)

### 1.1 Instalar requisitos
Opción recomendada (más simple en Windows): **XAMPP**.
- Instala XAMPP (Apache + PHP).
- Instala PostgreSQL (si no lo tienes).
- En `php.ini`, habilita extensiones:
  - `pdo_pgsql`
  - `pgsql`

### 1.2 Copiar el proyecto
Copia el proyecto a:
- `C:\xampp\htdocs\gimnasio-servidor\`

La app debe quedar en:
- `C:\xampp\htdocs\gimnasio-servidor\public\index.php`

### 1.3 Configurar base de datos (incluye tabla del módulo Limpieza)
En PC2, crea BD `gimnasio_db` y ejecuta el schema:
- `database/schema.sql` (esto crea `cleaning_schedules`)

Comando (si tienes `psql` en PATH):
```cmd
psql -d gimnasio_db -f C:\xampp\htdocs\gimnasio-servidor\database\schema.sql
```

### 1.4 Ajustar credenciales en `config/database.php`
Archivo:
- `C:\xampp\htdocs\gimnasio-servidor\config\database.php`

Valores típicos en Windows:
- `DB_HOST = 'localhost'`
- `DB_NAME = 'gimnasio_db'`
- `DB_USER = 'postgres'` (o tu usuario)
- `DB_PASS = 'TU_PASSWORD'` (si aplica)

### 1.5 Levantar el servidor
- Abre **XAMPP Control Panel**
- Start: **Apache**

Prueba local en PC2:
- `http://localhost/gimnasio-servidor/public/index.php`

Prueba el módulo Limpieza local (PC2):
- `http://localhost/gimnasio-servidor/public/index.php?controller=cleaning&action=index`

### 1.6 Permitir acceso desde la red
En Windows:
- Permite Apache (XAMPP) en el Firewall.

Prueba desde PC1 (macOS) directamente contra PC2:
- `http://172.17.99.101/gimnasio-servidor/public/index.php?controller=cleaning&action=index`

---

## 2) PC1 (macOS) - CLIENTE

Tienes 2 formas:

### Opción A (más rápida): PC1 solo consume el SERVIDOR
En macOS, abre en Safari/Chrome:
- `http://IP_PC2/gimnasio-servidor/public/index.php?controller=cleaning&action=index`

Con esto ya estás probando el despliegue distribuido (cliente en PC1, servidor en PC2).

### Opción B (más fiel a 2 nodos): PC1 como reverse proxy (Nginx)
Solo si quieres que el usuario entre a PC1 y PC1 reenvíe a PC2.

1) Instala nginx:
```bash
brew install nginx
```

2) Edita config de nginx y agrega un server que haga proxy a PC2.
- Objetivo: `http://IP_PC1/` -> `http://IP_PC2/`.

3) Inicia nginx:
```bash
brew services start nginx
```

4) Prueba desde PC1:
- `http://localhost/` (debería proxy-pasar a PC2)

> Nota: si usas la opción B, no necesitas CORS porque estás proxyando HTTP, no haciendo fetch cross-domain.

---

## 3) URLs de verificación del módulo Limpieza

- Lista:
  - `.../index.php?controller=cleaning&action=index`
- Crear:
  - `.../index.php?controller=cleaning&action=create`

---

## 4) Troubleshooting mínimo

- Si sale **"Controlador no encontrado"**:
  - Verifica que en `public/index.php` exista:
    - `'cleaning' => 'CleaningScheduleController'`
  - Verifica que exista el archivo:
    - `controllers/CleaningScheduleController.php`

- Si sale error de BD **"relation cleaning_schedules does not exist"**:
  - Ejecuta `database/schema.sql` en PostgreSQL de PC2.

- Si PC1 no abre `http://IP_PC2/...`:
  - Revisa firewall de Windows (permitir Apache)
  - Prueba `ping IP_PC2`
  - Asegúrate de usar la URL correcta (incluyendo `/gimnasio-servidor/public/`).
