"""
Python wrapper for Trivia Leaderboard API

"""

import requests
import json
import calendar
import time

from datetime import datetime, timedelta
from django.conf import settings

class TriviaAPI():
    def __init__(self, url, key):
        self.url = url
        self.key = key

    def get_leaderboard(self, amount):
        """
        Get the leaderboard for the last 7 days.
        """

        url = self.url + "/leaderboard"

        now = datetime.now()

        # Give someone the top ten players of all time
        # Our results are cached for four hours
        enddate = datetime(now.year, now.month, now.day, 23, 59, 0)
        startdate = datetime(2013, 11, 1, 0, 0, 0)

        # Convert datetime to unix time
        enddate = calendar.timegm(enddate.utctimetuple())
        startdate = calendar.timegm(startdate.utctimetuple())

        payload = {
            'start': startdate,
            'end': enddate,
            'amount': amount,
            'key': self.key
        }

        leaderboard_data = requests.post(url, params=payload)

        if not leaderboard_data.ok:
            leaderboard_data.raise_for_status()
            
        data = leaderboard_data.json()

        if data['response'] == "0":
            raise Exception(data["error"])
        
        return data['results']

    def get_user(self, master_id):
        """
        Get a single user's information
        """

        url = self.url + "/points"

        payload = {
            'master_id': master_id,
            'key': self.key
        }

        user_trivia_data = requests.post(url, params=payload)

        if not user_trivia_data.ok:
            user_trivia_data.raise_for_status()
            
        data = user_trivia_data.json()
        
        # Check if the user exists
        if data['response'] == "0":
            return data['error']
        else:
            return data['results'][0]
        
trivia = TriviaAPI(settings.TRIVIA_API_URL, settings.TRIVIA_API_KEY)
