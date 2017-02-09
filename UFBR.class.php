<?php

######################################################
#  UNIDADES FEDERATIVAS BRASIL v 0.1.3
#
#  Gustavo Arnosti Neves
#  Licença: GPL v2.1
#  Fev / 2017
#
# Eu procurava por dados das divisas entre estados
# Brasileiros para validar viagens de MDFe e não achei
# nada. Acabei montando essa classe com dados de 
# sites de geografia manualmente.
# 
# É uma classe simples e a simples declaração de
# estados e divisas pode ser útil a outras pessoas.
# 
# Validar rotas funciona bem. Criar rotas é funcional
# mas eu não gosto da implementação atual com trocentos
# loops foreach e sort por tamanho da string.
# 
######################################################


class UFBR {

    ######################################################
    #  Classe UFBR


    ######################################################
    ## DADOS INTERNOS


    # Flag Mensagens
    private $quiet = false;


    # Listagem dos estados com acentuacao
    private $estados = array(
        'AC' => 'Acre',
        'AL' => 'Alagoas',
        'AP' => 'Amapá',
        'AM' => 'Amazonas',
        'BA' => 'Bahia',
        'CE' => 'Ceará',
        'DF' => 'Distrito Federal',
        'ES' => 'Espírito Santo',
        'GO' => 'Goiás',
        'MA' => 'Maranhão',
        'MT' => 'Mato Grosso',
        'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais',
        'PA' => 'Pará',
        'PB' => 'Paraíba',
        'PR' => 'Paraná',
        'PE' => 'Pernambuco',
        'PI' => 'Piauí',
        'RJ' => 'Rio de Janeiro',
        'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul',
        'RO' => 'Rondônia',
        'RR' => 'Roraima',
        'SC' => 'Santa Catarina',
        'SP' => 'São Paulo',
        'SE' => 'Sergipe',
        'TO' => 'Tocantins',
    );


    # Fronteiras interestaduais e internacionais
    # de todos os estados brasileiros
    private $fronteiras = array(
        'AC' => array(
            'estados' => array('AM','RO'),
            'paises'  => array('Bolívia','Peru')),
        'AP' => array(
            'estados' => array('PA'),
            'paises'  => array('Suriname','Guiana Francesa')),
        'AM' => array(
            'estados' => array('MT','PA','RR','RO','AC'),
            'paises'  => array('Venezuela','Colômbia','Peru')),
        'PA' => array(
            'estados' => array('MT','TO','MA','AM','RR','AP'),
            'paises'  => array('Suriname','Guiana')),
        'RO' => array(
            'estados' => array('AC','AM','MT'),
            'paises'  => array('Bolívia')),
        'RR' => array(
            'estados' => array('AM','PA'),
            'paises'  => array('Venezuela','Guiana')),
        'TO' => array(
            'estados' => array('BA','PI','MA','GO','MT','PA'),
            'paises'  => array()),
        'AL' => array(
            'estados' => array('PE','SE','BA'),
            'paises'  => array()),
        'BA' => array(
            'estados' => array('AL','SE','PE','PI','MG','ES','GO','TO'),
            'paises'  => array()),
        'CE' => array(
            'estados' => array('PI','RN','PB','PE'),
            'paises'  => array()),
        'MA' => array(
            'estados' => array('PI','PA','TO'),
            'paises'  => array()),
        'PB' => array(
            'estados' => array('CE','PE','RN'),
            'paises'  => array()),
        'PE' => array(
            'estados' => array('AL','BA','PI','CE','PB'),
            'paises'  => array()),
        'PI' => array(
            'estados' => array('MA','TO','CE','PE','BA'),
            'paises'  => array()),
        'RN' => array(
            'estados' => array('CE','PB'),
            'paises'  => array()),
        'SE' => array(
            'estados' => array('AL','BA'),
            'paises'  => array()),
        'GO' => array(
            'estados' => array('TO','MT','MS','MG','BA','DF'),
            'paises'  => array()),
        'MT' => array(
            'estados' => array('AM','PA','TO','GO','MS','RO'),
            'paises'  => array('Bolívia')),
        'MS' => array(
            'estados' => array('MT','GO','SP','PR'),
            'paises'  => array('Paraguai','Bolívia')),
        'DF' => array(
            'estados' => array('GO','MG'),                           # Com MG
#           'estados' => array('GO'),                                # Sem MG
            'paises'  => array()),
        'ES' => array(
            'estados' => array('BA','MG','RJ'),
            'paises'  => array()),
        'MG' => array(
            'estados' => array('BA','RJ','ES','SP','GO','MS','DF'),  # Com DF
#           'estados' => array('BA','RJ','ES','SP','GO','MS'),       # Sem DF
            'paises'  => array()),
        'SP' => array(
            'estados' => array('MG','RJ','PR','MS'),
            'paises'  => array()),
        'RJ' => array(
            'estados' => array('SP','ES','MG'),
            'paises'  => array()),
        'PR' => array(
            'estados' => array('SP','MS','SC'),
            'paises'  => array('Argentina','Paraguai')),
        'RS' => array(
            'estados' => array('SC'),
            'paises'  => array('Argentina','Uruguai')),
        'SC' => array(
            'estados' => array('RS','PR'),
            'paises'  => array('Argentina'))
    );




    ######################################################
    ## CONSTRUTOR
    #  Deixa as listagens em orgem alfabetica
    #  Seta flag das Mensagens de texto

    public function __construct($quiet = false) {
        $quiet = is_bool($quiet) ? $quiet : false;

        ksort($this->fronteiras);
        foreach ($this->fronteiras as &$estado) {
            sort($estado['estados']);
            sort($estado['paises']);
        }

        ksort($this->estados);

        return $this;
    }




    ######################################################
    ## VALIDADORES


    # Verifica a validade de uma sigla de estado
    public function isSigla($sigla=null, $caseInsensitive=true) {
        $s = $caseInsensitive ? strtoupper($sigla) : $sigla;
        return in_array($s, array_keys($this->estados));
    }


    # Valida uma sigla de estado e imprime mensagem de erro
    # se necessario
    private function validaSigla($sigla=null, $caseInsensitive=true, $err='') {
        if ($this->isSigla($sigla, $caseInsensitive)) return true;
        if (!$this->quiet) {
            if (!empty($err)) echo "$err\n";
            echo "Sigla de estado invalida: $sigla\n";
        }
        return false;
    }


    # Prepara uma sigla de acordo com $caseInsensitive
    private function preparaSigla($sigla=null, $caseInsensitive=true) {
        if (!$caseInsensitive) return $sigla;
        return strtoupper($sigla);
    }


    # Verifica a validade de um nome de estado
    public function isEstado($estado=null, $caseInsensitive=true, $removeAcentos=true) {
        if (empty($estado) || !is_string($estado)) return false;
        $estado = $caseInsensitive ? strtolower($estado)          : $estado;
        $estado = $removeAcentos   ? $this->removeAcento($estado) : $estado;
        return in_array($estado, $this->estados($caseInsensitive, $removeAcentos));
    }


    # Verifica se dois estados fazem fronteira
    public function fazFronteira($sigla1=null, $sigla2=null, $caseInsensitive=true) {
        $err = "Erro ao verificar fronteiras entre UFs";
        if (!$this->validaSigla($sigla1, $caseInsensitive, $err)) return null;
        if (!$this->validaSigla($sigla2, $caseInsensitive, $err)) return null;
        $sigla1 = $this->preparaSigla($sigla1, $caseInsensitive);
        $sigla2 = $this->preparaSigla($sigla2, $caseInsensitive);
        return in_array($sigla1, $this->fronteirasUFs($sigla2));
    }




    ######################################################
    ## OBTENÇÃO DE DADOS


    # Retorna a lista de estados Brasileiros
    public function estados($lowerCase=false, $removeAcentos=false) {
        #if (!$lowerCase && !$removeAcentos) return $this->estados;
        $e = $this->estados;
        $e = $removeAcentos   ? array_map(array($this,  'removeAcento'), $e) : $e;
        $e = $lowerCase       ? array_map('strtolower',                  $e) : $e;
        return $e;
    }


    # Retorna a sigla de um estado atraves do seu nome
    public function siglaEstado($estado=null, $caseInsensitive=true, $removeAcentos=true) {
        if (empty($estado) || !$this->isEstado($estado, $caseInsensitive, $removeAcentos)) {
            echo "Nome de estado invalido: $estado\n";
            return null;
        }
        $e  = $removeAcentos   ? $this->removeAcento($e) : $e;
        $e  = $caseInsensitive ? strtolower($estado)     : $estado;
        $es = $this->estados($caseInsensitive, $removeAcentos);
        
        return array_search($e, $es);
        #return array_search($estado, $this->estados);
    }


    # Retorna um nome de um estado atraves de sua sigla
    public function nomeEstado($sigla=null, $caseInsensitive=true, $removeAcentos=false) {
        $err = "Erro ao buscar nome do Estado por sigla de UF";
        
        if (!$this->validaSigla($sigla, $caseInsensitive, $err)) return null;
        $sigla = $this->preparaSigla($sigla, $caseInsensitive);
        
        if (!isset($this->estados[$sigla])) return null;
        return $removeAcentos ? $this->removeAcento($this->estados[$sigla]) : $this->estados[$sigla];
    }


    # Retorna todas as fronteiras se $sigla for null ou ''
    # Ou retorna as fronteiras do estado de $sigla
    public function fronteiras($sigla=null, $caseInsensitive=true, $removeAcentos=false) {
        $fr = $this->fronteiras;
        if ($removeAcentos) {
            foreach ($fr as &$e) 
                foreach ($e as &$f) 
                    $f = array_map([$this, 'removeAcento'], $f);
        }

        if ($sigla === null || $sigla === '') return $fr;

        $err = "Erro ao buscar fronteiras de UF";
        if (!$this->validaSigla($sigla, $caseInsensitive, $err)) return null;
        
        $sigla = $this->preparaSigla($sigla, $caseInsensitive);
        return $fr[$sigla];
    }


    # Retorna nomes ao inves de siglas para as fronteiras
    # Retorna todas as fronteiras se $sigla for null ou ''
    # Ou retorna as fronteiras do estado de $sigla
    public function fronteirasNomes($sigla=null, $caseInsensitive=true, $removeAcentos=false) {
        $fr = $this->fronteiras($sigla, $caseInsensitive, $removeAcentos);
        if (empty($fr)) return null;
        
        if ($sigla == null | $sigla === '') {
            foreach($fr as &$e) {
                $e['estados'] = array_map(function($i) use ($caseInsensitive, $removeAcentos) {
                    return $this->nomeEstado($i, $caseInsensitive, $removeAcentos);
                    }, $e['estados']);
            }
            return $fr;
        }

        $fr['estados'] = array_map(function($i) use ($caseInsensitive, $removeAcentos) {
                return $this->nomeEstado($i, $caseInsensitive, $removeAcentos);
            }, $fr['estados']);

        return $fr;
    }


    # Retorna as siglas dos estados que fazem fronteira com $sigla
    public function fronteirasUFs($sigla=null, $caseInsensitive=true) {
        $err = "Erro ao buscar fronteiras de UFs por sigla de UF";
        if (!$this->validaSigla($sigla, $caseInsensitive, $err)) return null;
        $sigla = $this->preparaSigla($sigla, $caseInsensitive);
        $ret = $this->fronteiras[$sigla]['estados'];
        return $ret;
    }


    # Retorna os nomes dos estados que fazem fronteira com $sigla
    public function fronteirasUFsNomes($sigla=null, $caseInsensitive=true, $semAcento=false) {
        $f = $this->fronteirasUFs($sigla, $caseInsensitive);
        if (empty($f)) return null;
        
        $f = array_map(function($i) use ($caseInsensitive, $semAcento) {
            return $this->nomeEstado($i, false, $semAcento);
        }, $f);
        
        return $f;
    }


    # Retorna as fronteiras internacionais de um estado
    public function fronteirasPaises($sigla=null, $caseInsensitive=true, $semAcento=false) {
        $err = "Erro ao buscar fronteiras de paises por sigla de UF";
        if (!$this->validaSigla($sigla, $caseInsensitive, $err)) return null;
        $sigla = $this->preparaSigla($sigla, $caseInsensitive);
        $ret = $this->fronteiras($sigla, $caseInsensitive, $semAcento)['paises'];
        return $ret;
    }




    ######################################################
    ## ROTAS


    # Checa se uma rota fornecida é válida
    # FORMATO: ESTADO-ESTADO-ESTADO
    #      EX: SP-RJ-MG-BA
    public function checaRota($rota=null, $caseInsensitive=true) {
        if (empty($rota) || !is_string($rota)) return null;
        $err = "Erro na rota!\n  -> $rota";
        $r = explode('-', $rota);
        $r = $caseInsensitive ? array_map('strtoupper', $r) : $r;

        for($i = 0; $i < count($r)-1; $i++) {
            if (!$this->validaSigla($r[$i], false, $err)) return false;
            if (!$this->validaSigla($r[$i+1], false, $err)) return false;
            if ($r[$i] === $r[$i+1]) {
                echo "$err\nEstados de origem e destino são os mesmos!\n  " . $r[$i] . " => " . $r[$i+1] . "\n";
                return false;
            }
            if (!$this->fazFronteira($r[$i], $r[$i+1], false)) {
                echo "$err\n  -> " . $r[$i] . " não faz fronteira com " . $r[$i+1] . "\n";
                return false;
            }
        }
        echo "Rota validada com sucesso!\n";
        echo "  -> $rota\n";
        return true;
    }


    # Monta as rotas possiveis entre dois estados, sem repeticoes
    # EU REALMENTE QUERIA USAR FUNÇÕES RECURSIVAS, MAS TÁ DIFÍCIL
    # Montar assim me ajudou a entender a lógica, e funciona, mas
    # com certeza dá pra simplificar isso.
    public function montaRota($sigla1=null, $sigla2=null, $max=50, $caseInsensitive=true) {
        $err = "Erro ao montar rota";
        if (!$this->validaSigla($sigla1, $caseInsensitive, $err)) return null;
        if (!$this->validaSigla($sigla2, $caseInsensitive, $err)) return null;
        $sigla1 = $this->preparaSigla($sigla1, $caseInsensitive);
        $sigla2 = $this->preparaSigla($sigla2, $caseInsensitive);

        # Se o estado de origem e destino é o mesmo, rota simples
        # Talvez fosse melhor retornar erro nesse caso!
        if ($sigla1 === $sigla2) {
            return array("$sigla1-$sigla2");
        }

        $rotaMid = array();   # Vamos guardar as rotas aqui
        
        # Estado vizinhos
        $vizinhos = $this->fazFronteira($sigla1, $sigla2);
        if ($vizinhos === true) {
            $rotaMid[] = "$sigla1-$sigla2";
        }
        
        # Nao sao vizinhos nem o mesmo estado
        $front1 = $this->fronteirasUFs($sigla1);
        $front2 = $this->fronteirasUFs($sigla2);
        
        foreach($front1 as $f) 
        {
            if (in_array($f, $front2)) {
                $rotaMid[] = "$sigla1-$f-$sigla2";
            }
            $front3 = $this->fronteirasUFs($f, false);
            foreach($front3 as $f2) 
            {
                if ($f2 == $sigla1) continue;
                if (in_array($f2, $front2)) $rotaMid[] = "$sigla1-$f-$f2-$sigla2";
                $front4 = $this->fronteirasUFs($f2, false);
                foreach($front4 as $f3) 
                {
                    if ($f3 == $sigla1 || $f3 == $f) continue;
                    if (in_array($f3, $front2)) $rotaMid[] = "$sigla1-$f-$f2-$f3-$sigla2";
                    $front5 = $this->fronteirasUFs($f3, false);
                    foreach($front5 as $f4) 
                    {
                        if ($f4 == $sigla1 || $f4 == $f || $f4 == $f2) continue;
                        if (in_array($f4, $front2)) $rotaMid[] = "$sigla1-$f-$f2-$f3-$f4-$sigla2";
                        $front6 = $this->fronteirasUFs($f4, false);
                        foreach($front6 as $f5) 
                        {
                            if ($f5 == $sigla1 || $f5 == $f || $f5 == $f2 || $f5 == $f3) continue;
                            if (in_array($f5, $front2)) $rotaMid[] = "$sigla1-$f-$f2-$f3-$f4-$f5-$sigla2";
                            $front7 = $this->fronteirasUFs($f5, false);
                            foreach($front7 as $f6) 
                            {
                                if ($f6 == $sigla1 || $f6 == $f || $f6 == $f2 || $f6 == $f3 || $f6 == $f4) continue;
                                if (in_array($f6, $front2)) $rotaMid[] = "$sigla1-$f-$f2-$f3-$f4-$f5-$f6-$sigla2";
                                $front8 = $this->fronteirasUFs($f6, false);
                                foreach($front8 as $f7) 
                                {
                                    if ($f7 == $sigla1 || $f7 == $f || $f7 == $f2 || $f7 == $f3 || $f7 == $f4 || $f7 == $f5) continue;
                                    if (in_array($f7, $front2)) $rotaMid[] = "$sigla1-$f-$f2-$f3-$f4-$f5-$f6-$f7-$sigla2";
                                    $front9 = $this->fronteirasUFs($f7, false);
                                    foreach($front9 as $f8) 
                                    {
                                        if ($f8 == $sigla1 || $f8 == $f || $f8 == $f2 || $f8 == $f3 || $f8 == $f4 || $f8 == $f5 || $f8 == $f6) continue;
                                        if (in_array($f8, $front2)) $rotaMid[] = "$sigla1-$f-$f2-$f3-$f4-$f5-$f6-$f7-$f8-$sigla2";
                                        $front10 = $this->fronteirasUFs($f8, false);
                                        foreach($front10 as $f9) 
                                        {
                                            if ($f9 == $sigla1 || $f9 == $f || $f9 == $f2 || $f9 == $f3 || $f9 == $f4 || $f9 == $f5 || $f9 == $f6 || $f9 == $f7) continue;
                                            if (in_array($f9, $front2)) $rotaMid[] = "$sigla1-$f-$f2-$f3-$f4-$f5-$f6-$f7-$f8-$f9-$sigla2";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        usort($rotaMid, array($this, 'sortByLength'));
        if ($max === 'all' || $max === 'tudo') return $rotaMid;
        return array_slice($rotaMid, 0, $max);
    }




    ######################################################
    ## AUXILIARES


    # Usada com usort para index array de rotas por tamanho
    public function sortByLength($a,$b) {
        return strlen($a)-strlen($b);
    }


    # Remove acentuação de $string
    public function removeAcento($string) {
        if ( !preg_match('/[\x80-\xff]/', $string) )
            return $string;

        $chars = array(
        // Decompositions for Latin-1 Supplement
        chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
        chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
        chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
        chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
        chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
        chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
        chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
        chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
        chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
        chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
        chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
        chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
        chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
        chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
        chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
        chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
        chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
        chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
        chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
        chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
        chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
        chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
        chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
        chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
        chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
        chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
        chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
        chr(195).chr(191) => 'y',
        // Decompositions for Latin Extended-A
        chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
        chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
        chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
        chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
        chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
        chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
        chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
        chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
        chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
        chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
        chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
        chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
        chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
        chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
        chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
        chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
        chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
        chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
        chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
        chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
        chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
        chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
        chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
        chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
        chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
        chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
        chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
        chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
        chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
        chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
        chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
        chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
        chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
        chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
        chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
        chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
        chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
        chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
        chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
        chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
        chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
        chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
        chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
        chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
        chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
        chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
        chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
        chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
        chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
        chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
        chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
        chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
        chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
        chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
        chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
        chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
        chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
        chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
        chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
        chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
        chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
        chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
        chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
        chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
        );

        $string = strtr($string, $chars);

        return $string;
    }


}


######################################################
## ALGUNS TESTES

#$ufbr = new UFBR();
#foreach($ufbr->montaRota('rs','ro') as $rota) echo "$rota\n";
#var_dump($ufbr->checaRota('SP-rj-Mg-RJ', true));
#var_dump($ufbr->checaRota('SP-PR-SC-PR-MS-GO-TO-PA-AM'));
#var_dump($ufbr->estados(true, true));
#var_dump($ufbr->estados(true, false));
#var_dump($ufbr->estados(false, true));
#var_dump($ufbr->estados(false, false));
#var_dump($ufbr->nomeEstado('sp', false));
#var_dump($ufbr->nomeEstado('sP'));
#var_dump($ufbr->fronteiras(''));
#var_dump($ufbr->fronteiras('SP'));
#var_dump($ufbr->fronteirasNomes());
#var_dump($ufbr->fronteirasNomes('AM', false, true));
#var_dump($ufbr->fronteirasUFsNomes('TO', false, true));
#var_dump($ufbr->fronteirasPaises('pr'));
#var_dump($ufbr->estados());


?>
