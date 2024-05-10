#!/usr/bin/env python
# vim: set fileencoding=utf-8 :
#
# This file is part of Coruja-scripts
#
# Coruja-scripts is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, version 3 of the License.
#
# Coruja-scripts is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Coruja-scripts.  If not, see <http://www.gnu.org/licenses/>.
#
# Copyright 2011 Grupo Falabrasil - http://www.laps.ufpa.br/falabrasil
#
# Author 2011: Pedro Batista - pedosb@gmail.com

import sys
import os
import re
import subprocess
import tempfile

def check_result():
	sys.stdin.readline()

def exec_agi(command):
	sys.stdout.write(str(command) + '\n')
	sys.stdout.flush()
	check_result()

def main():
	while True:
		if sys.stdin.readline() == '\n':
			break

	jconf = sys.argv[1]
	grammar = sys.argv[2]
	exec_agi('VERBOSE "jconf %s"' % jconf)
	exec_agi('VERBOSE "grammar %s"' % grammar)

	wav_file = tempfile.NamedTemporaryFile(suffix='.wav', delete=False)
	exec_agi('VERBOSE "Initiaing julius"')
	julius = subprocess.Popen("""julius -separatescore -fallback1pass
			-nosectioncheck -C {0} -input stdin
			-gram {1}""".format(jconf, grammar).split(),
			stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=2)

	exec_agi('VERBOSE "Recording at least 2 seconds of silence or 10 seconds of audio in %s"' % wav_file.name)
	exec_agi('RECORD FILE %s wav 1 10000 BEEP s=2' % wav_file.name[:-4])

	wav_file.seek(0)
	exec_agi('VERBOSE "Sending audio to Julius"')

	if julius.poll() is not None:
		sys.stderr.writelines(julius.stdout.readlines())
		sys.stderr.flush()
		exec_agi('VERBOSE "Julius has terminated unexpected code %d"' %
				julius.returncode)

	julius.stdin.write(wav_file.read())
	julius.stdin.close()
	wav_file.close()

	exec_agi('VERBOSE "Waiting for Julius"')
	julius_out = ''.join(julius.stdout.readlines())

	result = re.search('^sentence1: (.*?)$', julius_out,
			re.MULTILINE | re.DOTALL).group(1)
	score = re.search('^cmscore1:(?P<score>( (\d\.\d{3}))+)$', julius_out,
			re.MULTILINE | re.DOTALL).group('score')
	exec_agi('VERBOSE "Julius finished (%s) %s"' % (result, score))
	exec_agi('SET VARIABLE RECRESULT "%s"' % result)
	exec_agi('SET VARIABLE RECCONFIDENCEPERWORD "%s"' % score)
	exec_agi('SET VARIABLE RECCONFIDENCE "%s"' %
			(sum(map(float, score.split())) / len(score.split())))

if __name__=='__main__':
	main()
