@extends('layouts.admin-template')
@section('title'){{__("textes.utilisateursAdmin_page_nom")}}@endsection
@push('script')
    <script>
        $(document).on("click", ".open-supprimer", function () {
            let userId = $(this).data('id');
            let userEmail = $(this).data('email');
            $(".modal-footer #userId").val(userId);
            $(".modal-body #userEmail").text(userEmail);
        });
    </script>
@endpush
@section('content')
    <div class="index-name-div">
        <h1 class="index-name-text">{{__('textes.utilisateursAdmin_index_title')}}</h1>
    </div>
    <div class="container">
        <!-- Créer un formulaire qui va permettre de chercher des praticiens dans la liste en appelant la fonction praticiens.search -->
        <form method="POST" action="{{ route('utilisateurs.search') }}" class="d-flex mb-5">
        @csrf
        <!-- Champs permettant à l'utilisateur de rentrer ce qu'il souhaite chercher et qui être transmis par la suite au code -->
            <input class="form-control me-2 list-search" type="text" name="search"
                   placeholder="{{__('textes.utilisateursAdmin_index_search_placeholder')}}"
                   @if(isset($search)) value="{{ $search }}" @endif aria-label="Search">
            <!-- Bouton affichant une icone et permettant de valider la recherche -->
            <button class="btn btn-small" type="submit"><i class="fas fa-search icon-size icon-white"></i></button>

            <!-- Si l'utilisateur a effectué une recherche -->
        @if(isset($search))
            <!-- Affiche une îcone qui ramène l'utilisateur vers la page contenant la liste de tous les praticiens -->
                <a href="{{ route('utilisateurs.index') }}">
                    <button type="button" class="btn-close ms-2 x-size" aria-label="Close"></button>
                </a>
            @endif

        </form>

        @if(!empty($utilisateurs))

            <table class="w-100 table">
                <!-- Titre des catégories du tableau -->
                <thead class="w-100">
                <tr>
                    <th class="liste-titre">{{__('textes.utilisateursAdmin_index_email')}}</th>
                    <th class="liste-titre">{{__('textes.utilisateursAdmin_index_nom')}}</th>
                    <th class="liste-titre">{{__('textes.utilisateursAdmin_index_prenom')}}</th>
                    <th class="liste-titre">{{__('textes.utilisateursAdmin_index_roles')}}</th>
                    <th class="liste-titre">{{__('textes.utilisateursAdmin_index_vip')}}</th>
                    <th class="liste-titre">{{__('textes.utilisateursAdmin_index_sub')}}</th>
                    <th class="liste-titre">{{__('textes.utilisateursAdmin_index_supp')}}</th>
                </tr>
                </thead>
                <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>
                <!-- Pour chaque praticiens récupérés du tableau praticiens et contenu dans praticiens -->
                @foreach($utilisateurs as $key=>$utilisateur)
                    <tbody>
                    <tr style="background:{{ $key%2===0?"#FBEFEF":"white" }}">
                        <!-- Affiche le code du praticiens -->
                        <th class="bord-color"><span class="liste-text"> {{ $utilisateur->email}} </span></th>
                        <th class="bord-color"><span class="liste-text"> @isset($utilisateur->name)
                            {{ $utilisateur->name?:""}}
                        @endisset </span></th>
                        <th class="bord-color"><span class="liste-text"> @isset($utilisateur->firstname)
                            {{ $utilisateur->firstname?:""}}
                        @endisset </span></th>
                        <!-- On affiche le nom du praticiens qui si cliqué amène l'utilisateur vers ce praticiens -->
                        <td class="bord-color">
                                <span class="liste-text">
                                    <form method="POST" onsubmit="return validation();" enctype="multipart/form-data"
                                          action="{{route('utilisateurs.edit')}}" target="dummyframe">
                                    @csrf
                                        <select name="role_id" id="role_id" onchange="this.form.submit()">
                                            @foreach($roles as $role)
                                                <option @if($role->id==$utilisateur->role_id) selected
                                                        @else @endif value={{$role->id}}>{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="user_id" value="{{$utilisateur->id}}"/>
                                    </form>
                                </span>
                        </td>
                        <td class="bord-color">
                                <span class="liste-text">
                                    <form method="POST" onsubmit="return validation();" enctype="multipart/form-data"
                                          action="{{route('utilisateurs.vip')}}" target="dummyframe">
                                    @csrf
                                        <select name="status" id="status" onchange="this.form.submit()">
                                            <option value="true"
                                                    @if(config('roles.models.defaultUser')::find($utilisateur->id)->hasRole("vip")) selected @endif >OUI</option>
                                            <option value="false"
                                                    @if(!config('roles.models.defaultUser')::find($utilisateur->id)->hasRole("vip")) selected @endif >NON</option>
                                        </select>
                                        <input type="hidden" name="user_id" value="{{$utilisateur->id}}"/>
                                    </form>
                                </span>
                        </td>
                        <td class="bord-color"><span class="liste-text">  @isset($utilisateur->sub_end_at) <?php echo gmdate("Y-m-d H:i:s", $utilisateur->sub_end_at)?>@endisset</span></td>
                        <td class="bord-color text-center">
                                <span class="liste-text">
                                    <button type="button" class="btn-close open-supprimer"
                                            data-id="{{$utilisateur->id}}" data-email="{{$utilisateur->email}}"
                                            data-toggle="modal" data-target="#exampleModal"></button>
                                </span>
                        </td>
                    </tr>
                    </tbody>
                @endforeach
            </table>
        @else
            <div>
                <!-- On affiche un message expliquant qu'aucun praticiens n'a été trouvé -->
                <em class="fs-5">{{__("textes.conseillersAdmin_index_aucun")}}</em>
            </div>
        @endif
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Supprimer l'utilisateur?</h5>
                        <button type="button" class="btn-close " data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="bold" id="userEmail"></p>
                        <p>Êtes-vous sur de vouloir supprimer l'utilisateur?</p>
                    </div>
                    <div class="modal-footer">
                        <form method="POST" action="{{route("utilisateurs.delete")}}">
                            @csrf
                            <input type="hidden" id="userId" name="userId"/>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn-primary-close rounded">Confirmer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
