<template>
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-8">
                <!-- {{ this.$store.state.teste }}
                <button @click="this.$store.state.teste = 'Modifiquei o valor da store do Vuex'">Teste</button> -->

                <!-- início do card de busca -->
                <card-component titulo="Busca de marcas">
                    <template v-slot:conteudo>
                        <div class="row g-3">

                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp"
                                    texto-ajuda="Opcional. Informe o ID da marca">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp"
                                        placeholder="ID" v-model="busca.id">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome da marca" id="inputNome" id-help="nomeHelp"
                                    texto-ajuda="Opcional. Informe o nome da marca">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp"
                                        placeholder="Nome da marca" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end"
                            @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>
                <!-- fim do card de busca -->


                <!-- início do card de listagem de marcas -->
                <card-component titulo="Relação de marcas">
                    <template v-slot:conteudo>
                        <table-component :dados="marcas.data"
                            :visualizar="{ visivel: true, dataBsToggle: 'modal', dataBsTarget: '#modalMarcaVisualizar' }"
                            :atualizar="{ visivel: true, dataBsToggle: 'modal', dataBsTarget: '#modalMarcaAtualizar' }"
                            :remover="{ visivel: true, dataBsToggle: 'modal', dataBsTarget: '#modalMarcaRemover' }"
                            :titulos="{
                                id: { titulo: 'ID', tipo: 'texto' },
                                nome: { titulo: 'Nome', tipo: 'texto' },
                                imagem: { titulo: 'Imagem', tipo: 'imagem' },
                                created_at: { titulo: 'Criação', tipo: 'data' },
                            }"></table-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in marcas.links" :key="key"
                                        :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal"
                                    data-bs-target="#modalMarca">Adicionar</button>
                            </div>
                        </div>
                    </template>

                </card-component>
                <!-- fim do card de listagem de marcas -->
            </div>
        </div>
        <!-- Início do modal de inclusão de marca -->
        <modal-component id="modalMarca" titulo="Adicionar marca">

            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso"
                    v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a marca"
                    v-if="transacaoStatus == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="novoNome" id-help="novoNomeHelp"
                        texto-ajuda="Informe o nome da marca">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp"
                            placeholder="Nome da marca" v-model="nomeMarca">
                    </input-container-component>
                    {{ nomeMarca }}
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp"
                        texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control" id="novoImagem" aria-describedby="novoImagemHelp"
                            placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                    {{ arquivoImagem }}
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
        <!-- Fim do modal de inclusão de marca -->

        <!-- início do modal de visualização de marca -->
        <modal-component id="modalMarcaVisualizar" titulo="Visualizar marca">
            <template v-slot:alertas></template>
            <template v-slot:conteudo>
                <!-- {{ this.$store.state.item }} -->
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="this.$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Nome da marca">
                    <input type="text" class="form-control" :value="this.$store.state.item.nome">
                </input-container-component>

                <input-container-component titulo="Imagem">
                    <img :src="'storage/' + this.$store.state.item.imagem" v-if="this.$store.state.item.imagem">
                </input-container-component>

                <input-container-component titulo="Data de criação">
                    <input type="text" class="form-control" :value="this.$store.state.item.created_at">
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!-- fim do modal de visualização de marca -->

        <!-- início do modal de remoção de marca -->
        <modal-component id="modalMarcaRemover" titulo="Remover marca">
            <template v-slot:alertas>
                <alert-component tipo='success' titulo="Transação realizada com sucesso!"
                    :detalhes="this.$store.state.transacao" v-if="this.$store.state.transacao.status == 'sucesso'" />
                <alert-component tipo="danger" titulo="Erro na transação." :detalhes="this.$store.state.transacao"
                    v-if="this.$store.state.transacao.status == 'erro'" />
            </template>
            <template v-slot:conteudo v-if="this.$store.state.transacao.status != 'sucesso'">
                <!-- {{ this.$store.state.item }} -->
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="this.$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Nome da marca">
                    <input type="text" class="form-control" :value="this.$store.state.item.nome">
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()"
                    v-if="this.$store.state.transacao.status != 'sucesso'">Remover</button>
            </template>
        </modal-component>
        <!-- fim do modal de remoção de marca -->

        <!-- Início do modal de atualização de marca -->
        <modal-component id="modalMarcaAtualizar" titulo="Atualizar marca">

            <template v-slot:alertas>
                <alert-component tipo='success' titulo="Transação realizada com sucesso!"
                    :detalhes="this.$store.state.transacao" v-if="this.$store.state.transacao.status == 'sucesso'" />
                <alert-component tipo="danger" titulo="Erro na transação." :detalhes="this.$store.state.transacao"
                    v-if="this.$store.state.transacao.status == 'erro'" />
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="novoNome" id-help="novoNomeHelp"
                        texto-ajuda="Informe o nome da marca">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp"
                            placeholder="Nome da marca" v-model="this.$store.state.item.nome">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp"
                        texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control" id="atualizarImagem"
                            aria-describedby="atualizarImagemHelp" placeholder="Selecione uma imagem"
                            @change="carregarImagem($event)">
                    </input-container-component>
                </div>

                <!-- {{ this.$store.state.item }} -->
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>
        </modal-component>
        <!-- Fim do modal de atualização de marca -->

    </div>
</template>

<script>
import axios from 'axios';
import Paginate from './Paginate.vue'
export default {
    components: { Paginate },
    data() {
        return {
            urlBase: 'http://localhost:8000/api/v1/marca',
            urlPaginacao: '',
            urlFiltro: '',
            nomeMarca: '',
            arquivoImagem: [],
            transacaoStatus: '',
            transacaoDetalhes: {},
            marcas: { data: [] },
            busca: { id: '', nome: '' }
        }
    },
    methods: {
        atualizar() {
            // console.log('nome atualizado', this.$store.state.item.nome)
            // console.log('imagem', this.arquivoImagem)
            console.log('http', 'patch')

            let formData = new FormData();
            formData.append('_method', 'patch')
            formData.append('nome', this.$store.state.item.nome)

            if (this.arquivoImagem[0]) {
                formData.append('imagem', this.arquivoImagem[0])
            }


            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            }

            let url = this.urlBase + '/' + this.$store.state.item.id


            axios.post(url, formData, config)
                .then(response => {
                    //console.log('Registro atualizado com sucesso!', response)
                    this.$store.state.transacao.status = 'sucesso'
                    this.$store.state.transacao.mensagem = 'Registro de marca atualizado com sucesso!'
                    // limpar campo de seleção de arquivos
                    atualizarImagem.value = ''
                    this.carregarLista()

                })
                .catch(errors => {
                    //console.log('Houve um erro na tentativa de atualização do registro.', errors.response)
                    this.$store.state.transacao.status = 'erro'
                    this.$store.state.transacao.mensagem = errors.response.data.message
                    this.$store.state.transacao.dados = errors.response.data.errors


                })
        },
        remover() {
            let confirmacao = confirm('Tem certeza que deseja remover este registro?')

            if (!confirmacao) {
                return false
            };

            let formData = new FormData();
            formData.append('_method', 'delete')

            // headers interceptado pelo axios, não há necessidade de declarar mais aqui
            // let config = {
            //     headers: {
            //         Accept: 'application/json',
            //         Authorization: this.token
            //     }
            // }

            let url = this.urlBase + '/' + this.$store.state.item.id
            // let url = this.urlBase + '/1450' // forçar erro para teste

            axios.post(url, formData)// removido o terceiro parametro de configuração (config)
                .then(response => {
                    //console.log('Registro removido com sucesso!', response)
                    this.$store.state.transacao.status = 'sucesso'
                    this.$store.state.transacao.mensagem = response.data.msg
                    this.carregarLista()
                })
                .catch(errors => {
                    //console.log('Houve um erro na tentativa de remoção do registro.', errors.response)
                    this.$store.state.transacao.status = 'erro'
                    this.$store.state.transacao.mensagem = errors.response.data.erro
                })
        },
        pesquisar() {
            let filtro = ''
            for (let chave in this.busca) {

                if (this.busca[chave]) {

                    if (filtro != '') {
                        filtro += ";"
                    }

                    filtro += chave + ':like:' + this.busca[chave]
                }
            }
            // console.log(filtro)
            if (filtro != '') {
                this.urlPaginacao = 'page=1'
                this.urlFiltro = '&filtro=' + filtro
            } else {
                this.urlFiltro = ''
            }

            this.carregarLista() // requisitando novamente os dados para a api

        },
        paginacao(l) {
            if (l.url) {
                // this.urlBase = l.url //ajustando a url de consulta com o parâmetro de página
                this.urlPaginacao = l.url.split('?')[1]
                this.carregarLista() //requisitando novamente os dados para nossa API
            }
        },
        carregarLista() {
            let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro

            // let config = {
            //     headers: {
            //         'Accept': 'application/json',
            //         'Authorization': this.token
            //     }
            // }

            axios.get(url) // removido segundo parametro (config) porque não há necessidade como o do metodo remover
                .then(response => {
                    this.marcas = response.data
                    // console.log(this.marcas)
                })
                .catch(errors => {
                    console.log(errors)
                })
        },
        carregarImagem(e) {
            this.arquivoImagem = e.target.files
        },
        salvar() {
            console.log(this.nomeMarca, this.arquivoImagem[0])

            let formData = new FormData();
            formData.append('nome', this.nomeMarca)
            formData.append('imagem', this.arquivoImagem[0])

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    /* interceptado pelo axios */
                    // 'Accept': 'application/json',
                    // 'Authorization': this.token
                }
            }

            axios.post(this.urlBase, formData, config)
                .then(response => {
                    this.transacaoStatus = 'adicionado'
                    this.transacaoDetalhes = {
                        mensagem: 'ID do registro: ' + response.data.id
                    }

                    console.log(response)
                })
                .catch(errors => {
                    this.transacaoStatus = 'erro'
                    this.transacaoDetalhes = {
                        mensagem: errors.response.data.message,
                        dados: errors.response.data.errors
                    }
                    //errors.response.data.message
                })
        }
    },
    mounted() {
        this.carregarLista()
    }
}
</script>