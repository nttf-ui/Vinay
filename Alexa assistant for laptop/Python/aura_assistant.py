import os
import struct
import datetime
import webbrowser
import requests
import speech_recognition as sr
import pyttsx3
import pyaudio
import pvporcupine
import wikipediaapi
import pywhatkit
import pyautogui
import platform
import subprocess
import json
from ctypes import cast, POINTER
from comtypes import CLSCTX_ALL
from pycaw.pycaw import AudioUtilities, IAudioEndpointVolume

# --- NEW ---
# Import the library to play the activation sound
from playsound import playsound

# --- INITIALIZATION ---

# 1. Initialize Text-to-Speech Engine
engine = pyttsx3.init('sapi5')
voices = engine.getProperty('voices')
engine.setProperty('voice', voices[1].id)


def speak(text):
    """This function takes text and speaks it out loud."""
    print(f"Alexa: {text}")
    engine.say(text)
    engine.runAndWait()


# 2. Initialize Wake Word Engine
# --- SECURITY WARNING: YOUR KEYS WERE REMOVED. PLEASE USE PLACEHOLDERS. ---
PICOVOICE_ACCESS_KEY = "zV9QA/k5DAFuoA/tBHnlJBQ+X0im8Rsfdqvl0cKwPVazzGt2qL7bGg=="
porcupine = None
pa = None
audio_stream = None
keyword_paths = ['Alexa_en_windows_v3_0_0.ppn']


# 3. Initialize APIs
wiki_wiki = wikipediaapi.Wikipedia(
    'AlexaAssistant/1.0 (youremail@example.com)')

# --- API KEYS ---
# --- SECURITY WARNING: YOUR KEYS WERE REMOVED. PLEASE USE PLACEHOLDERS. ---
OPENWEATHER_API_KEY = "04f8d95f7dfa8e6e9c9e638c3355ca4e"
NEWS_API_KEY = "bbb75ac7efca441783e975c116f63358"


def take_command():
    """Listens for a command and returns it as text."""
    r = sr.Recognizer()
    with sr.Microphone() as source:
        print("Listening for command...")
        r.pause_threshold = 1
        r.adjust_for_ambient_noise(source)
        try:
            audio = r.listen(source, timeout=5, phrase_time_limit=5)
            print("Recognizing...")
            command = r.recognize_google(audio, language='en-us').lower()
            print(f"User said: {command}\n")
            return command
        except Exception as e:
            return "None"


def execute_command(command):
    """Executes the given command."""
    if 'hello' in command:
        speak("Hello! How can I assist you today?")

    # ... (all your other elif commands remain exactly the same) ...
    elif 'wikipedia' in command:
        speak('Searching Wikipedia...')
        query = command.replace("wikipedia", "").strip()
        page = wiki_wiki.page(query)
        if page.exists():
            speak(f"According to Wikipedia, {page.summary[0:200]}...")
        else:
            speak(
                f"Sorry, I could not find any results for {query} on Wikipedia.")

    elif 'open youtube' in command:
        playsound('Okay.mp3')
        speak("Opening YouTube...")
        webbrowser.open("youtube.com")

    elif 'open google' in command:
        playsound('Okay.mp3')
        speak("Opening Google...")
        webbrowser.open("google.com")

    elif 'the time' in command:
        playsound('Okay.mp3')
        str_time = datetime.datetime.now().strftime("%I:%M %p")
        speak(f"The time is {str_time}")

    elif 'search for' in command:
        playsound('Okay.mp3')
        query = command.replace("search for", "").strip()
        speak(f"Searching Google for {query}")
        pywhatkit.search(query)

    elif 'play' in command:
        playsound('Okay.mp3')
        song = command.replace("play", "").strip()
        speak(f"Playing {song} on YouTube")
        pywhatkit.playonyt(song)

    elif 'open microsoft' in command or 'open edge' in command:
        playsound('Okay.mp3')
        speak("Opening Microsoft Edge.")
        edge_path = "C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe"
        os.startfile(edge_path)

    elif 'open notepad' in command:
        playsound('Okay.mp3')
        speak("Opening Notepad.")
        os.system("start notepad")

    elif 'open calculator' in command:
        playsound('Okay.mp3')
        speak("Opening Calculator.")
        os.system("start calc")

    elif 'open download' in command:
        playsound('Okay.mp3')
        speak("Opening your downloads folder.")
        downloads_path = os.path.join(os.path.expanduser('~'), 'Downloads')
        os.startfile(downloads_path)

    elif 'close this window' in command:
        playsound('Okay.mp3')
        speak("Closing window.")
        pyautogui.hotkey('alt', 'f4')

    elif 'switch tab' in command:
        playsound('Okay.mp3')
        speak("Switching tab.")
        pyautogui.hotkey('ctrl', 'tab')

    elif 'type' in command:
        playsound('Okay.mp3')
        text_to_type = command.replace("type", "").strip()
        speak(f"Typing: {text_to_type}")
        pyautogui.write(text_to_type, interval=0.1)

    elif 'weather in' in command:
        playsound('Okay.mp3')
        city = command.replace("weather in", "").strip()
        speak(f"Getting the weather for {city}...")
        try:
            url = f"http://api.openweathermap.org/data/2.5/weather?q={city}&appid={OPENWEATHER_API_KEY}&units=metric"
            res = requests.get(url)
            data = res.json()
            temp = data['main']['temp']
            description = data['weather'][0]['description']
            speak(
                f"The temperature is {temp} degrees Celsius with {description}.")
        except Exception as e:
            speak("Sorry, I couldn't fetch the weather information for that city.")

    elif 'news' in command:
        playsound('Okay.mp3')
        speak("Fetching the latest news headlines...")
        try:
            url = f"https://newsapi.org/v2/top-headlines?country=us&apiKey={NEWS_API_KEY}"
            res = requests.get(url)
            data = res.json()
            articles = data['articles'][:3]
            for i, article in enumerate(articles):
                speak(f"Headline {i+1}: {article['title']}")
        except Exception as e:
            speak("Sorry, I couldn't fetch the news right now.")

    elif 'lock the computer' in command or 'lock screen' in command:
        speak("Locking the computer.")
        os.system("rundll32.exe user32.dll,LockWorkStation")

    elif 'shutdown' in command or 'shut down' in command:
        speak("Are you sure you want to shut down the computer?")
        confirmation = take_command()
        if 'yes' in confirmation:
            speak("Shutting down.")
            os.system("shutdown /s /t 1")
        else:
            speak("Shutdown cancelled.")

    elif 'restart' in command:
        speak("Are you sure you want to restart the computer?")
        confirmation = take_command()
        if 'yes' in confirmation:
            speak("Restarting.")
            os.system("shutdown /r /t 1")
        else:
            speak("Restart cancelled.")

    elif 'good bye' in command or 'exit' in command or 'stop' in command:
        playsound('goodbye.mp3')
        speak("Goodbye! Shutting down.")
        return True

    else:
        pass

    return False


def run_alexa():
    global porcupine, pa, audio_stream
    try:
        porcupine = pvporcupine.create(
            access_key=PICOVOICE_ACCESS_KEY,
            keyword_paths=keyword_paths
        )

        pa = pyaudio.PyAudio()
        audio_stream = pa.open(
            rate=porcupine.sample_rate,
            channels=1,
            format=pyaudio.paInt16,
            input=True,
            frames_per_buffer=porcupine.frame_length
        )

        speak("Alexa assistant initialized. Waiting for wake word.")
        print(
            f"Listening for '{os.path.basename(keyword_paths[0]).split('_')[0]}'...")

        while True:
            pcm = audio_stream.read(porcupine.frame_length)
            pcm = struct.unpack_from("h" * porcupine.frame_length, pcm)
            keyword_index = porcupine.process(pcm)

            if keyword_index >= 0:
                print("Wake word detected!")

                # --- MODIFIED SECTION ---
                try:
                    # Play the activation sound instead of saying "Yes?"
                    playsound('activate.mp3')
                except Exception as e:
                    print(
                        f"Couldn't play sound: {e}. Is activate.wav in the folder?")
                    # Fallback to speaking if sound fails
                    speak("Yes?")

                command = take_command()
                if command and command != "None":
                    should_exit = execute_command(command)
                    if should_exit:
                        break

    finally:
        if audio_stream is not None:
            audio_stream.close()
        if pa is not None:
            pa.terminate()
        if porcupine is not None:
            porcupine.delete()
        print("Resources cleaned up. Goodbye!")


if __name__ == '__main__':
    run_alexa()
