<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste - Modal Click Outside</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Teste - Modal Click Outside</h1>
        
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-lg font-semibold mb-4">Teste de Modal</h2>
            <button onclick="openTestModal()" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Abrir Modal de Teste
            </button>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Console Log</h2>
            <div id="consoleLog" class="bg-black text-green-400 p-4 rounded font-mono text-sm h-32 overflow-y-auto">
                <!-- Logs aparecerão aqui -->
            </div>
        </div>
    </div>

    <!-- Modal de Teste -->
    <div id="testModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" onclick="handleTestModalClick(event)">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Modal de Teste</h3>
                    <button onclick="closeTestModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 mb-4">Este é um modal de teste.</p>
                    <p class="text-gray-600 text-sm mb-4">Clique fora do modal para fechá-lo.</p>
                    <div class="flex justify-end space-x-3">
                        <button onclick="closeTestModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button onclick="closeTestModal()" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            OK
                        </button>
                    </div>
                </div>
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

        function openTestModal() {
            addLog('Abrindo modal de teste...');
            document.getElementById('testModal').classList.remove('hidden');
        }

        function closeTestModal() {
            addLog('Fechando modal de teste...');
            document.getElementById('testModal').classList.add('hidden');
        }

        function handleTestModalClick(event) {
            addLog('=== DEBUG: handleTestModalClick chamado ===');
            addLog('Event target ID: ' + event.target.id);
            addLog('Event target class: ' + event.target.className);
            addLog('Event currentTarget ID: ' + event.currentTarget.id);
            
            // Fecha o modal se clicar exatamente no backdrop ou no div interno
            if (event.target.id === 'testModal' || event.target.classList.contains('flex')) {
                addLog('Fechando modal (clique no backdrop)...');
                closeTestModal();
            } else {
                addLog('Clique não foi no backdrop, modal permanece aberto');
            }
        }

        // Log inicial
        addLog('Página de teste carregada');
        addLog('Função handleTestModalClick disponível: ' + (typeof handleTestModalClick === 'function'));
    </script>
</body>
</html>
