Deploying a Django application with Nginx and uWSGI involves several steps. Here's a general overview of the process:

1. **Install Required**:
   ```bash
   # Nginx:
   sudo apt-get install -y nginx
   # Nginx:
   sudo apt-get install -y pthon3
   #python:
   sudo apt-get install -y python3-pip python3-virtualenv
   ```

2. **Create Virtual Envirnment in Project Directory**:
   ```bash
   virtualenv <project_directory>/.venv
   ```
   Update `<project_directory>`.

3. **Install uWsgi and package from requirement.txt**
   ```bash
   source <project_directory>/.venv/bin/activate
   pip install uwsgi
   pip install -r requirement.txt
   ```
   Update `<project_directory>`.

3. **Create a uWSGI Systemd Service for Project**:
   Create a systemd service file, e.g., `<user_home_directory>/<app_name>_uwsgi.service`:
   ```ini
   [Unit]
   Description=uWSGI service for <project_name>
   After=network.target

   [Service]
   User=root
   WorkingDirectory=<project_directory>
   ExecStart=<project_directory>/.venv/bin/uwsgi --http-socket :<app_port> --module <app_name>.wsgi

   [Install]
   WantedBy=multi-user.target
   ```
   Update `<user_home_directory>`, `<project_name>`, `<project_directory>`, `<app_port>`, `<app_name>` and other details accordingly.

4. **Create Symbolic link for service**:
   ```bash
   sudo ln -s <project_directory>/<app_name>_uwsgi.service /etc/systemd/system/<app_name>_uwsgi.service
   ```
   Update `<app_name>`.

5. **enable and start service**:
   ```bash
   sudo systemctl enable <app_name>_uwsgi.service
   sudo systemctl start <app_name>_uwsgi.service
   ```
   Update `<app_name>`.

4. **Configure Nginx vhost**:
   ```nginx
   server {
      listen 80;
      server_name your_domain.com;  # Change this to your domain or server IP

      location = /favicon.ico { access_log off; log_not_found off; }
      location /static/ {
            root /home/firstdjango/htdocs/firstdjango;
      }

      location / {
            # include uwsgi_params;
            # uwsgi_pass unix:/home/firstdjango/htdocs/firstdjango.sock;
            proxy_pass http://127.0.0.1:8090/;
      }
   }
   ```
   - Update required details
   - Enable the Nginx server block: `sudo ln -s /etc/nginx/sites-available/myapp_nginx.conf /etc/nginx/sites-enabled`
   - Test the Nginx configuration: `sudo nginx -t`
   - Reload Nginx: `sudo systemctl reload nginx`

5. **Configure Django Settings**:
   Update your Django settings to serve static and media files properly when deployed with Nginx. Add something like the following:
   ```python
   STATIC_URL = '/static/'
   STATIC_ROOT = '/home/firstdjango/htdocs/firstdjango/static/'

   MEDIA_URL = '/media/'
   MEDIA_ROOT = '/home/firstdjango/htdocs/firstdjango/media/'
   ```

6. **Testing**:
   - Ensure your Django project's virtual environment is present and active.
   - Collect static files: `python manage.py collectstatic`
   - Test the uWSGI service and Nginx setup to make sure your application is running: 
     - Test uWSGI: `uwsgi --http-socket :<app_port> --module <app_name>.wsgi`
     - Access your site in a web browser or use `curl` to check it's working through Nginx: `curl http://your_domain.com`
   
That's a basic overview of deploying a Django application with Nginx and uWSGI. Be sure to adapt the paths, domain names, and other configurations according to your specific setup.