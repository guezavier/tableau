import os
import json
import re

# 📂 Chemin du dossier contenant les fichiers JSON
directory = r"C:\Users\20xs170270\Documents\PORTABLE APP\UwAmp\www\zsluxmodules\config\data"  # <-- adapte le chemin

def slugify(text: str) -> str:
    """Crée un identifiant simple à partir d'un label."""
    text = text.lower()
    text = re.sub(r"[^a-z0-9]+", "_", text)  # remplace caractères spéciaux par _
    return text.strip("_")

for filename in os.listdir(directory):
    if filename.lower().endswith(".json"):
        filepath = os.path.join(directory, filename)
        
        with open(filepath, "r", encoding="utf-8") as f:
            old_data = json.load(f)

        # 🔹 Récupère le nom du fichier sans extension = label
        label = os.path.splitext(filename)[0].capitalize()

        fields = []
        for key, values in old_data.items():
            if isinstance(values, list):
                for v in values:
                    fields.append({
                        "name": slugify(v),
                        "label": v,
                        "type": "select",
                        "options": ["SO", "OK", "NOK"],
                        "remark": True
                    })

        new_data = {
            "label": label,
            "fields": fields
        }

        # 🔹 Écriture dans le fichier (remplace l’ancien contenu)
        with open(filepath, "w", encoding="utf-8") as f:
            json.dump(new_data, f, ensure_ascii=False, indent=2)

        print(f"✅ Fichier transformé : {filename}")
