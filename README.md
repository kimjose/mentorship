# E-Mentorship Tool

[![Build Status](https://travis-ci.org/username/repo.svg?branch=master)](https://travis-ci.org/username/repo)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE.md)
[![Version](https://img.shields.io/badge/version-1.0.0-green.svg)](https://semver.org/)

This is a mentorship tool that assists persons doing mentorship record data observed during mentorship. The intended users are persons providing mentorship to various facilities within the country [Kenya](https://en.wikipedia.org/wiki/Kenya). It is composed of various checklists which can be modified or added as required. These checklists contain questions related to the mentoship being done. These checklists are what guide the person during the mentorship process. Anything not in the checklists can be recorded under findings. 

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Introduction

This is a mentorship tool that assists persons doing mentorship record data observed during mentorship. The intended users are persons providing mentorship to various facilities within the country [Kenya](https://en.wikipedia.org/wiki/Kenya). It is composed of various checklists which can be modified or added as required. These checklists contain questions related to the mentoship being done. These checklists are what guide the person during the mentorship process. Anything not in the checklists can be recorded under findings. Here is a [live link](https://ess.mgickenya.org/) to the tool.

## Features

- Checklist Management - The tool provides users with an interface to add or modify existing checklists. With this questions can be added and the tool customized.
- Facility Management - Users of this tool are able to add facilities they will be supporting.
- Facility visit - Users are able to add facility visit and track what has been done during that visit.
- Reports and analytics - The tool provides various reports based on what data has been fed into the system.

## Installation
### Requirements
- php 8.1 and above - Install php 8.1 or above on the computer intended to run this tool. 
- MySQL v*.0 + - Install MySQL on the computer.
- Apache server - 


### Server app set up

  - Clone/download the repository.
  - Install the libraries using composer.
        composer install
      <i>PS: You might need to install and/or enable various php extensions.[gd], [zip], [json], [dom], [curl]  </i>
       
  - Copy contents of .env.example to a file called .env. Run the following 
          
          cp .env.example .env
      <i>Update contents according to your environment</i>
  - Copy contents of .htaccess.example to a file called .htaccess. Run the following 
          
          cp .htaccess.example .htaccess
      <i>This will control affect how the site is accessed.</i>

  - Enable mod headers and rewite for apache2.
   Incase rewrite fails as per given htaccess add the following line to mime conf to enable php file extension scan for extension-less urls. <br>

         AddType application/x-httpd-php .php 



## Usage
 Update your .env file to map your configurations.

         DB_HOST=localhost
         DB_DRIVER=mysql
         DB_USER=user
         DB_PASSWORD=PASSWORD
         DB_NAME=DB

## Contributing
 - Francis Kimonye

## License
This project is licensed under the [License Name] - see the [LICENSE.md](LICENSE.md) file for details.
