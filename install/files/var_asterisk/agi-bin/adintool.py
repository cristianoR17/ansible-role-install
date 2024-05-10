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
import subprocess
import re
import tempfile

import julius as util

jconf="/usr/src/comunix-wp01.1/lapsam-comunix-1.1/lapsam-comunix-1.1.x86.jconf"
grammar="/usr/src/comunix-wp01.1/example/giraffas/sim_nao"
plugindir="/usr/src/comunix-wp01.1/adinplugin"

def main():
	while True:
		if sys.stdin.readline() == '\n':
			break

	jconf = sys.argv[1].strip()
	grammar = sys.argv[2].strip()
	util.exec_agi('VERBOSE "jconf %s"' % jconf)
	util.exec_agi('VERBOSE "grammar %s"' % grammar)

	with open(grammar + '.voca') as f:
		voca = [w.strip() for w in f.readlines()]
	with open(grammar + '.ret') as f:
		ret = dict()
		for t in re.findall('^(?P<id>\w+)\s<>(?P<ret>.*?)<>', ''.join(f.readlines()), re.MULTILINE | re.DOTALL):
			ret[t[0]] = t[1]

	raw_file = tempfile.NamedTemporaryFile(suffix='.raw', delete=False)

	util.exec_agi('VERBOSE "Initiaing julius"')

	command = """/usr/src/julius-4.2/julius/julius -separatescore -fallback1pass
			-nosectioncheck -C {0}
			-gram {1} -plugindir {2} -input psb""".format(jconf, grammar, plugindir)

	julius = subprocess.Popen(command.split(), stdin=subprocess.PIPE,
			stdout=subprocess.PIPE, stderr=2, env=dict(RAWFILE=raw_file.name))

	util.exec_agi('VERBOSE "Waiting for Julius log in %s"' % raw_file.name)
	util.exec_agi('STREAM FILE /var/lib/asterisk/sounds/en/beep ""')
	util.exec_agi('STREAM FILE /var/lib/asterisk/sounds/en/beep ""')

	while True:
		line = julius.stdout.readline()
		if line == '':
			break
		sys.stderr.write(line)
		sys.stderr.flush()
		result = re.search('^sentence1: (.*?)$', line, re.DOTALL)
		if result is not None:
			line = julius.stdout.readline()
			sys.stderr.write(line)
			sys.stderr.flush()
			wseq = map(int, re.search('^wseq1: (.*?)$', line, re.DOTALL).group(1).split())
			line = julius.stdout.readline()
			sys.stderr.write(line)
			sys.stderr.flush()
			line = julius.stdout.readline()
			sys.stderr.write(line)
			sys.stderr.flush()
			score = re.search('^cmscore1:(?P<score>( (\d\.\d{3}))+)$', line, re.DOTALL)
			if score is not None:
				julius.kill()
				score = score.group('score')
				result = result.group(1)
				break
	util.exec_agi('VERBOSE "Julius finished (%s) %s"' % (result, score))
	try:
		util.exec_agi('SET VARIABLE RECRET "%s"' % ret[re.match('^#(.*)_WORD_\d+$', voca[wseq[1]]).group(1)])
	except:
		util.exec_agi('SET VARIABLE RECRET ""')
	util.exec_agi('SET VARIABLE RECRESULT "%s"' % result)
	util.exec_agi('SET VARIABLE RECCONFIDENCEPERWORD "%s"' % score)
	util.exec_agi('SET VARIABLE RECCONFIDENCE "%s"' %
			(sum(map(float, score.split())) / len(score.split())))

if __name__=='__main__':
	main()
