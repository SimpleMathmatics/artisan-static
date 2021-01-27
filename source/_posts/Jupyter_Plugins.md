---
title: '02 - JupyterLab Verbessern'
date: 2021-01-25
image: https://www.tng-project.org/static/data/lab_logo_tng.png
---

# JupyterLab Verbesserungen

## Einführung

<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Jupyter_logo.svg/1200px-Jupyter_logo.svg.png" alt="Project Jupyter – Wikipedia" style="zoom:10%;" />					 

Um Jupyter auf einem Multi-User-Server zu benutzen, verwendet man *JupyterHub*. Hier können Nutzer verwaltet und Umgebungen konfiguriert werden. Es gibt zwei mögliche Interfaces,  welche auf JupyterHub aufgesetzt werden können: *JupyterLab* und *The Jupyter notebook*.  

Wenn man *JupyterLab* auf einem System installiert, welches *JupyterHub* betreibt, so ist *JupyterLab* direkt unter der `/lab`  URL erreichbar. *The Jupyter notebook* ist weiterhin unter `/tree`  verfügbar. Der default kann ganz einfach im *JupyterHub* config file (`jupyterhub_config.py`) geändert werden.

In `jupyterhub_config.py`  kann man Dinge wie Datenbank Konfigurationen oder User Management beeinflussen. Will man aber Details wie Benutzeroberflächen, git Integration oder andere Kleinigkeiten verändern, gibt es eine Vielzahl an Extensions (etwa für das JupyterLab). 

![Image for post](https://miro.medium.com/max/2199/1*P0B1z-38LkFEXZuA5RER8A.png)

Auf Wunsch des Kursleiters, schauen wir uns nun an, wie man bei JupyterLab die Option von Version Control bereitstellt. Auch wurde von Kommilitonen angefragt, ob man nicht standardmäßig tensorflow vorinstallieren kann. 

## Beispiele

### Version Control via Git in JupyterLab

<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Git-logo.svg/1200px-Git-logo.svg.png" alt="Git – Wikipedia" style="zoom:15%;" />	



Es ist sehr schwierig diffs in jupyter notebooks zu erkennen, wenn man git mit dem klassischen Terminal bedient, da die jupyter notebooks (.ipynb) als JSON file abgespeichert werden. Um diesem Problem entgegenzuwirken, wurden einige open source Lösungen entwickelt. Ich stelle hier eine Lösung für JupyterLab mit dem Namen [jupyterlab-git](https://github.com/jupyterlab/jupyterlab-git) vor. 

#### Installation

Im folgenden Dockerfile habe ich eine Lösung bereitgestellt, wie man Git in JupyterLab bereitstellt. Das Dockerimage habe ich auch auf Dockerhub zu Verfügung gestellt und kann mit 

```bash
$ docker pull simplemathmatics/jupyter
```

gezogen werden. 

Hier das Dockerfile:

```bash
# build on top of python 3.8
FROM python:3.8

WORKDIR /jup

# nodejs is required for the lab built
RUN apt install curl
RUN curl -sL https://deb.nodesource.com/setup_6.x | bash -
RUN apt-get install -y nodejs
RUN apt-get install -y npm

# install jupyter libraries
RUN pip install --no-cache-dir jupyter
RUN pip install --no-cache-dir jupyterlab==2.0.0

# install the git extension
RUN pip install jupyterlab-git

# start the extensions (in labversion 2.0.0 it does not auto-start)
RUN jupyter serverextension enable --py jupyterlab_git --sys-prefix

# build the lab
RUN jupyter lab build

# run the lab and expose to port 8888
EXPOSE 8888
ENTRYPOINT ["jupyter", "lab","--ip=0.0.0.0","--allow-root"]
```



##### Demo

![preview.gif](https://github.com/jupyterlab/jupyterlab-git/blob/master/docs/figs/preview.gif?raw=true)





## Tensorflow vorinstallieren

Im Tensorflow standardmäßig zu installieren, reicht es, den pip install im Jupyterlab Dockerfile durchzuführen: 

```bash
# build on top of python 3.8
FROM python:3.8

WORKDIR /jup

# nodejs is required for the lab built
RUN apt install curl
RUN curl -sL https://deb.nodesource.com/setup_6.x | bash -
RUN apt-get install -y nodejs
RUN apt-get install -y npm

# install jupyter libraries
RUN pip install --no-cache-dir jupyter
RUN pip install --no-cache-dir jupyterlab==2.0.0

# install the git extension
RUN pip install jupyterlab-git

# install tensorflow
RUN pip install tensorflow

# start the extensions (in labversion 2.0.0 it does not auto-start)
RUN jupyter serverextension enable --py jupyterlab_git --sys-prefix

# build the lab
RUN jupyter lab build

# run the lab and expose to port 8888
EXPOSE 8888
ENTRYPOINT ["jupyter", "lab","--ip=0.0.0.0","--allow-root"]
```



## Quellen



https://towardsdatascience.com/version-control-with-jupyter-notebooks-f096f4d7035a (git)

https://github.com/jupyterlab/jupyterlab-git (git)

https://jupyterlab.readthedocs.io/en/stable/user/jupyterhub.html (jupyterLab Integration)

https://jupyterlab.readthedocs.io/en/stable/user/extensions.html (notebook extensions)