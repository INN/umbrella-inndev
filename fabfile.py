import os

from fabric.api import *
from fabric import colors
from fabric.operations import put


"""
Base configuration
"""
env.project_name = 'inndev'
env.file_path = '.'
env.ignore_files_containing = [
    '.py',
    '.pyc',
    '.git',
    'requirements.txt',
]


# Environments
def production():
    """
    Work on production environment
    """
    env.settings = 'production'
    env.hosts = [os.environ['INNDEV_PRODUCTION_SFTP_HOST'], ]
    env.user = os.environ['INNDEV_PRODUCTION_SFTP_USER']
    env.password = os.environ['INNDEV_PRODUCTION_SFTP_PASSWORD']


def staging():
    """
    Work on staging environment
    """
    env.settings = 'staging'
    env.hosts = [os.environ['INNDEV_STAGING_SFTP_HOST'], ]
    env.user = os.environ['INNDEV_STAGING_SFTP_USER']
    env.password = os.environ['INNDEV_STAGING_SFTP_PASSWORD']

def stable():
    """
    Work on stable branch.
    """
    print(colors.green('On stable'))
    env.branch = 'stable'


def master():
    """
    Work on development branch.
    """
    print(colors.yellow('On master'))
    env.branch = 'master'


def branch(branch_name):
    """
    Work on any specified branch.
    """
    print(colors.red('On %s' % branch_name))
    env.branch = branch_name


def theme(name):
    env.file_path = 'wp-content/themes/%s/' % name


def deploy():
    """
    Deploy local copy of repository to target environment
    """
    require('settings', provided_by=["production", "staging", ])
    require('branch', provided_by=[master, stable, branch, ])

    local('git checkout %s' % env.branch)
    local('git submodule update --init --recursive')

    # Never include files that haven't been added to the repo
    ignore_untracked()

    for f in find_file_paths(env.file_path):
        if env.file_path == '.':
            put(local_path=f[0], remote_path='/%s' % f[0])
        else:
            put(local_path=f[1], remote_path='/%s' % f[1])

def find_file_paths(directory):
    """
    A generator function that recursively finds all files in the
    upload directory.
    """
    for root, dirs, files in os.walk(directory):
        rel_path = os.path.relpath(root, directory)
        for f in files:
            # Skip dot files
            if f.startswith('.'):
                continue

            if rel_path == '.':
                one, two = f, os.path.join(root, f)
            else:
                one, two = os.path.join(rel_path, f), os.path.join(root, f)

            # Skip any files we explicitly say to ignore
            skip = False
            for s in env.ignore_files_containing:
                if s in one or s in two:
                    skip = True
                    break

            if skip:
                continue

            yield(one, two)


def ignore_untracked():
    """
    Grabs list of files that haven't been added to the git repo and
    adds them to `env.ignore_files_containing`.
    """
    result = local('git ls-files --others --exclude-standard', capture=True)
    if result:
        for line in result.splitlines():
            env.ignore_files_containing.append(line)
