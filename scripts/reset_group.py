from pathlib import Path

from base_loader import LoadBase

if __name__ == "__main__":

    dir = Path(__file__).parent.parent
    fichier_base = dir.joinpath("base.csv")
    save_dir = dir.joinpath("saves")

    ark = LoadBase(fichier_base, dir, save_dir)

    # ----------------------------------
    #           actions
    #-----------------------------------
    # group_to_reset = 't' # Si c'est une lettre, supprime cette lettre de toutes les références groupes des chorés
    group_to_reset = None

    #group_to_create, source_file = "t", dir.joinpath("source.csv")
    group_to_create, source_file = None, None

    compare_groups, group_to_add = ["t", "s"], "w"
    #compare_groups, group_to_add = None, None


    # Execution
    if group_to_reset is not None:
        ark.reset_groupe(group_to_reset)

    if group_to_create is not None and source_file is not None:
        ark.create_event(group_to_create, source_file)

    if compare_groups is not None and group_to_add is not None:
        ark.compare_and_add_group(compare_groups, group_to_add)
