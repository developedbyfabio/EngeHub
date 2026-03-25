<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teste - Sistema de Permissões</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Teste - Sistema de Permissões</h1>
        
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-lg font-semibold mb-4">Teste de Função JavaScript</h2>
            <button onclick="testOpenPermissionsModal()" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Testar openPermissionsModal
            </button>
            <div id="testResult" class="mt-4 p-4 bg-gray-50 rounded hidden">
                <h3 class="font-semibold">Resultado do Teste:</h3>
                <div id="testContent"></div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-lg font-semibold mb-4">Teste de Rota</h2>
            <button onclick="testRoute()" 
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Testar Rota /admin/system-logins/4/permissions
            </button>
            <div id="routeResult" class="mt-4 p-4 bg-gray-50 rounded hidden">
                <h3 class="font-semibold">Resultado da Rota:</h3>
                <div id="routeContent"></div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-lg font-semibold mb-4">Teste de Save Permissions</h2>
            <button onclick="testSavePermissions()" 
                    class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                Testar savePermissions(4)
            </button>
            <div id="saveResult" class="mt-4 p-4 bg-gray-50 rounded hidden">
                <h3 class="font-semibold">Resultado do Save:</h3>
                <div id="saveContent"></div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Console Log</h2>
            <div id="consoleLog" class="bg-black text-green-400 p-4 rounded font-mono text-sm h-32 overflow-y-auto">
                <!-- Logs aparecerão aqui -->
            </div>
        </div>
    </div>

    <script>
        // Função para adicionar log ao console visual
        function addLog(message) {
            const consoleDiv = document.getElementById('consoleLog');
            const timestamp = new Date().toLocaleTimeString();
            consoleDiv.innerHTML += `[${timestamp}] ${message}\n`;
            consoleDiv.scrollTop = consoleDiv.scrollHeight;
        }

        // Função para testar openPermissionsModal
        function testOpenPermissionsModal() {
            addLog('=== TESTE: openPermissionsModal ===');
            
            // Verificar se a função existe
            if (typeof window.openPermissionsModal === 'function') {
                addLog('✅ Função openPermissionsModal encontrada');
                addLog('Chamando openPermissionsModal(4)...');
                
                try {
                    window.openPermissionsModal(4);
                    addLog('✅ Função chamada com sucesso');
                } catch (error) {
                    addLog('❌ Erro ao chamar função: ' + error.message);
                }
            } else {
                addLog('❌ Função openPermissionsModal não encontrada');
                addLog('Tipo de window.openPermissionsModal: ' + typeof window.openPermissionsModal);
            }
            
            // Mostrar resultado
            document.getElementById('testResult').classList.remove('hidden');
            document.getElementById('testContent').innerHTML = 
                typeof window.openPermissionsModal === 'function' ? 
                '✅ Função encontrada e chamada' : 
                '❌ Função não encontrada';
        }

        // Função para testar a rota
        function testRoute() {
            addLog('=== TESTE: Rota de Permissões ===');
            
            fetch('/admin/system-logins/4/permissions', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                addLog(`Status da resposta: ${response.status}`);
                return response.json();
            })
            .then(data => {
                addLog('Dados recebidos: ' + JSON.stringify(data));
                
                // Mostrar resultado
                document.getElementById('routeResult').classList.remove('hidden');
                document.getElementById('routeContent').innerHTML = 
                    data.success ? 
                    '✅ Rota funcionando - ' + (data.html ? 'HTML recebido' : 'Sem HTML') : 
                    '❌ Erro: ' + (data.message || 'Erro desconhecido');
            })
            .catch(error => {
                addLog('❌ Erro na requisição: ' + error.message);
                
                // Mostrar resultado
                document.getElementById('routeResult').classList.remove('hidden');
                document.getElementById('routeContent').innerHTML = '❌ Erro: ' + error.message;
            });
        }

        // Função para testar savePermissions
        function testSavePermissions() {
            addLog('=== TESTE: savePermissions ===');
            
            // Verificar se a função existe
            if (typeof window.savePermissions === 'function') {
                addLog('✅ Função savePermissions encontrada');
                
                // Simular evento para a função
                const mockEvent = {
                    target: {
                        innerHTML: 'Teste',
                        disabled: false
                    }
                };
                
                // Substituir temporariamente o event global
                const originalEvent = window.event;
                window.event = mockEvent;
                
                try {
                    addLog('Chamando savePermissions(4)...');
                    window.savePermissions(4);
                    addLog('✅ Função chamada com sucesso');
                    
                    // Mostrar resultado
                    document.getElementById('saveResult').classList.remove('hidden');
                    document.getElementById('saveContent').innerHTML = '✅ Função encontrada e chamada';
                } catch (error) {
                    addLog('❌ Erro ao chamar função: ' + error.message);
                    
                    // Mostrar resultado
                    document.getElementById('saveResult').classList.remove('hidden');
                    document.getElementById('saveContent').innerHTML = '❌ Erro: ' + error.message;
                } finally {
                    // Restaurar event original
                    window.event = originalEvent;
                }
            } else {
                addLog('❌ Função savePermissions não encontrada');
                addLog('Tipo de window.savePermissions: ' + typeof window.savePermissions);
                
                // Mostrar resultado
                document.getElementById('saveResult').classList.remove('hidden');
                document.getElementById('saveContent').innerHTML = '❌ Função não encontrada';
            }
        }

        // Log inicial
        addLog('Página de teste carregada');
        addLog('CSRF Token: ' + document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    </script>
</body>
</html>
