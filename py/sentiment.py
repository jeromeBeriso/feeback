import sys
import json
from googletrans import Translator
from textblob import TextBlob

def analyze_sentiment(text):
    translator = Translator()
    translated_text = translator.translate(text, src="tl", dest="en").text  # Translate Filipino → English
    analysis = TextBlob(translated_text)
    polarity = analysis.sentiment.polarity

    if polarity > 0:
        sentiment = "Positive"
        verdict = "The results indicate that the student is satisfied with the professor's performance."
    elif polarity < 0:
        sentiment = "Negative"
        verdict = "The results indicate that the student is dissatisfied with the professor's performance and further evaluation is needed."
    else:
        sentiment = "Neutral"
        verdict = "The results indicate that the student has neither expressed satisfaction nor dissatisfaction with the professor's performance."

    return {"sentiment": sentiment, "verdict": verdict}

if __name__ == "__main__":
    input_text = sys.argv[1]  # Get input from PHP script
    result = analyze_sentiment(input_text)
    print(json.dumps(result))
