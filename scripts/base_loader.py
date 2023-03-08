import datetime
import shutil
from pathlib import Path

class LoadBase():
    def __init__(self, base, dir, save_dir):
        self.file = base
        self.working_dir = dir
        self.save_dir = save_dir
        self.lignes = []

        # Sauve les informations dans un autre fichier
        file_date_format = datetime.datetime.strftime(datetime.datetime.utcnow(),"%Y%m%d%H%M%S")
        fichier_save = save_dir.joinpath( f"save_{file_date_format}.csv")

        print(f"Fichier base sauvegardé dans {fichier_save}")
        shutil.copy(base, fichier_save)

        with open(base, 'r') as ficin:
            for ligne in ficin.readlines():
                self.lignes.append(ligne)

    def compare_and_add_group(self, compare_groups, group_to_add):
        """ Cherche si les groupes de compare_groups sont affectés à une choré. Si oui, ajoute le tag group_to_add """
        for ind, ligne in enumerate(self.lignes):
            if ligne.strip() != "" and ligne[0] != "#":
                infos = ligne.split(";")
                groups = infos[1]

                match = True
                for group in compare_groups:
                    if groups.count(group)==0:
                        match = False
                if match:
                    infos[1] = infos[1] + group_to_add
                    print("Add : ", ";".join(infos))
                self.lignes[ind] = ";".join(infos) 
        self.export()


    def create_event(self, group, source):
        """ Parcours le fichier source et pour chaque occurence, ajoute un indicateur de groupe """
        # Charge la liste des danses à grouper
        to_find = []
        with open(source, 'r') as ficin:
            for ligne in ficin.readlines():
                ligne = ligne.strip()
                if ligne != "":
                    to_find.append(ligne)

        # Matche avec la liste d'archive
        matchs = []
        for ind, ligne in enumerate(self.lignes):
            infos = ligne.split(";")
            if infos[0].upper() in to_find:
                matchs.append(infos[0])
                infos[1] = f"{infos[1]}{group}"
                self.lignes[ind] = ";".join(infos)
                to_find.remove(infos[0].upper()) 

        self.export()
        
        # Affiche une stat
        print(f"Danses insérées ({len(matchs)}) :")
        for find in matchs:
            print (f"\t{find}")

        print(f"Danses non trouvées ({len(to_find)}) :")
        for find in to_find:
            print (f"\t{find}")

    def export(self):
        """ Recrée le fichier de base à partir des informations de l'archive """
        with open(self.file, 'w') as ficout:
            for ligne in self.lignes:
                ficout.write(ligne)

    def reset_groupe(self, group):
        """ Retire la lettre group de tous les blocs groupes """
        for ind, ligne in enumerate(self.lignes):
            if ligne.strip() != "" and ligne[0] != "#":
                infos = ligne.split(";")
                infos[1] = infos[1].replace(group, '')
                self.lignes[ind] = ";".join(infos)
        self.export()
